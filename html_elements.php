<?php
interface Renderable
{
    public function render($indent_unit, $indent_level);
}

class TextElement implements Renderable
{
    public $content;

    public function __construct($text_content) {
        $this->content = htmlentities($text_content);
    }

    public function render($indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);
        $output = "";
        $text_content = $this->content;

        if ($text_content == "") {
            $output = "";
        } else {
            $output = $indent . $text_content . "\n";
        }
        
        return $output;
    }
}

abstract class HtmlMarkup implements Renderable
{
    public static $OPENING = "<";
    public static $CLOSING = ">";

    public static $EMPTYCLOSING = " />";
    public static $PAIREDCLOSING = "</";

    public static $ATTROPEN = '="';
    public static $ATTRCLOSE = '"';

    public static $IDATTR = "id";
    public static $CLASSATTR = "class";

    public static $SEPARATOR = " ";

    protected $name;
    protected $attributes;
    protected $children;

    private function render_attributes() {
        $rendering_pieces = array();
        foreach ($this->attributes as $name=>$value) {
            $rendering_piece = "";

            if ($value != "") {
                $rendering_piece = $name . self::$ATTROPEN . $value . self::$ATTRCLOSE;
                array_push($rendering_pieces, $rendering_piece);
            } else {
                $rendering_piece = "";
            }
        }
        $attribute_rendering = implode(self::$SEPARATOR, $rendering_pieces);
        return $attribute_rendering;
    }

    private function render_empty($opening, $indent) {
        $rendering = $opening . self::$EMPTYCLOSING;
        $formatted = $indent . $rendering . "\n";
        return $formatted;
    }

    private function render_paired($opening, $indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);

        $openline = $opening . self::$CLOSING;

        $midlines = "";
        $child_level = $indent_level + 1;

        foreach ($this->children as $child) {
            $midlines .= $child->render($indent_unit, $child_level);
        }

        $closeline = self::$PAIREDCLOSING . $this->name . self::$CLOSING;

        $formatted_open = $indent . $openline . "\n";
        $formatted_mid = $midlines;
        $formatted_close = $indent . $closeline . "\n";

        $formatted = $formatted_open . $formatted_mid . $formatted_close;
        return $formatted;
    }

    public function render($indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);
        $name = $this->name;

        $opening = self::$OPENING . $name;
        $attribute = $this->render_attributes();

        if ($attribute != "") {
            $opening .= self::$SEPARATOR . $attribute;
        } else {
            // attribute is empty and has nothing te be put into opening
            $opening = $opening; 
        }

        $output = "";

        if ($this->children === NULL) {
            $output = $this->render_empty($opening, $indent);
        } else {
            // shorten variable names to follow the 80-column rule
            $unit = $indent_unit;
            $level = $indent_level;
            $output = $this->render_paired($opening, $unit, $level);
        }

        return $output;
    }

    public function set_id($value_string) {
        $value = htmlentities($value_string);
        $this->attributes[self::$IDATTR] = $value;
    }

    public function add_class($value_string) {
        $value = htmlentities($value_string);
        $existing = $this->attributes[self::$CLASSATTR];

        if (strpos($value, self::$SEPARATOR)) {
            // class value cannot contain space, if so do not add it
            return FALSE;
        } else if ($existing == "") {
            $this->attributes[self::$CLASSATTR] = $value;
            return TRUE;
        } else if (strpos($existing, $value_string)) {
            // class already exists, do nothing
            return FALSE;
        } else {
            $added = $existing . self::$SEPARATOR . $value;
            $this->attributes[self::$CLASSATTR] = $added;
            return TRUE;
        }
    }
}

class HtmlElement extends HtmlMarkup
{   
    public static $TAGNAME = "html";

    public static $CHARSET = "UTF-8";

    private $head;
    private $body;

    public function __construct($title_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();        
        $this->head = new HeadElement($this, self::$CHARSET, $title_string);
        $this->body = new BodyElement($this);
        array_push($this->children, $this->head);
        array_push($this->children, $this->body);
    }

    public function head_push(Renderable $element) {
        $this->head->push($element);
    }

    public function body_push(Renderable $element) {
        $this->body->push($element);
    }
}

class AElement extends HtmlMarkup
{
    public static $TAGNAME = "a";

    public static $HREFATTR = "href";
    public static $TARGETATTR = "target";

    public static $BLNKWINDOW = "_blank";

    public function __construct($href_url, $link_text) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$HREFATTR] = $href_url;
        $this->attributes[self::$TARGETATTR] = "";

        $this->children = array();
        $content_element = new TextElement($link_text);
        array_push($this->children, $content_element);
    }

    public function set_hyperlink($reference_url) {
        $this->attributes[self::$HREFATTR] = $reference_url;
    }

    public function use_blankwindow() {
        $this->attributes[self::$TARGETATTR] = self::$BLNKWINDOW;
    }
}

class BodyElement extends HtmlMarkup
{
    public static $TAGNAME = "body";

    public function __construct(HtmlMarkup $parent) {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
    }
}

class BrElement extends HtmlMarkup
{
    public static $TAGNAME = "br";

    public function __construct() {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = NULL;
    }
}

class CharsetMetaElement extends HtmlMarkup
{
    public static $TAGNAME = "meta";

    public static $CHARSETATTR = "charset";

    public function __construct(HeadElement $parent, $charset_value) { 
        $this->name = self::$TAGNAME;
        
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$CHARSETATTR] = $charset_value;

        $this->children = NULL;        
    }

    public function set_charset($value) {
        $this->attributes[self::$CHARSETATTR] = $$value;
    }
}

class DivElement extends HtmlMarkup
{
    public static $TAGNAME = "div";

    public function __construct() {      
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();
    }
}

class FormElement extends HtmlMarkup
{
    public static $TAGNAME = "form";

    public static $ACTIONATTR = "action";
    public static $METHODATTR = "method";
    public static $ENCTYPEATTR = "enctype";

    public static $GETMTHD = "get";
    public static $POSTMTHD = "post";

    public function __construct($action_handler) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$ACTIONATTR] = $action_handler;
        $this->attributes[self::$METHODATTR] = self::$GETMTHD;
        $this->attributes[self::$ENCTYPEATTR] = "";

        $this->children = array();        
    }

    public function set_action($handler_url) {
        $this->attributes[self::$ACTIONATTR] = $handler_url;
    }

    public function use_post() {
        $this->attributes[self::$METHODATTR] = self::$POSTMTHD;
    }

    public function set_encryption($type) {
        $this->attributes[self::$ENCTYPEATTR] = $type;
    }

    public function push_textinput($name, $default_value) {
        $input = new TextInputElement($this, $name, $default_value);
        array_push($this->children, $input);
        return $input;
    }

    public function push_passwordinput($name, $default_value) {
        $input = new PasswordInputElement($this, $name, $default_value);
        array_push($this->children, $input);
        return $input;
    }

    public function push_textarea($name, $default_value) {
        $area = new TextareaElement($this, $name, $default_value);
        array_push($this->children, $area);
        return $area;
    }
}

class H1Element extends HtmlMarkup
{
    public static $TAGNAME = "h1";

    public function __construct($content_string) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H2Element extends HtmlMarkup
{
    public static $TAGNAME = "h2";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();        
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H3Element extends HtmlMarkup
{
    public static $TAGNAME = "h3";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();           
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H4Element extends HtmlMarkup
{
    public static $TAGNAME = "h4";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H5Element extends HtmlMarkup
{
    public static $TAGNAME = "h5";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H6Element extends HtmlMarkup
{
    public static $TAGNAME = "h6";

    public function __construct($content_string) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class HeadElement extends HtmlMarkup
{
    public static $TAGNAME = "head";

    public function __construct(HtmlMarkup $parent, $charset, $title) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $charset = new CharsetMetaElement($this, $charset);
        $title = new TitleElement($this, $title);
        array_push($this->children, $charset);
        array_push($this->children, $title);
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
    }
}

class HrElement extends HtmlMarkup
{
    public static $TAGNAME = "hr";

    public function __construct() {        
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>""); 
        $this->children = NULL;    
    }
}

class ImgElement extends HtmlMarkup
{
    public static $TAGNAME = "img";

    public static $SRCATTR = "src";
    public static $ALTATTR = "alt";
    public static $TITLEATTR = "title";

    public function __construct($src_value, $alt_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$SRCATTR] = $src_value;
        $this->attributes[self::$ALTATTR] = $alt_value;

        $this->children = NULL;
    }

    public function set_source($image_url) {
        $this->attriubutes[self::$SRCATTR] = $image_url;
    }

    public function set_alternative($info_text) {
        $encoded_text = htmlentities($info_text);
        $this->attributes[self::$ALTATTR] = $encoded_text;
    }

    public function set_title($text) {
        $encoded_text = htmlentities($text);
        $this->attriubutes[self::$TITLEATTR] = $encoded_text;
    }
}

class TextInputElement extends HtmlMarkup
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "text";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";

    public function __construct(FormElement $form, $name, $default_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $default_value;
        $this->attributes[self::$MAXLENGTHATTR] = "";        

        $this->children = NULL;
    }

    public function set_maxlength($integer_value) {
        $this->attributes[self::$MAXLENGTHATTR] = $integer_value;
    }
}

class PasswordInputElement extends HtmlMarkup
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "password";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";
    
    public function __construct(FormElement $form, $name, $default_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $default_value;
        $this->attributes[self::$MAXLENGTHATTR] = "";

        $this->children = NULL;
    }

    public function set_maxlength($integer_value) {
        $this->attriubutes[self::$MAXLENGTHATTR] = $integer_value;
    }
}

class LinkElement extends HtmlMarkup
{
    public static $TAGNAME = "link";

    public static $HREFATTR = "href";
    public static $TYPEATTR = "type";
    public static $RELATTR = "rel";

    public function __construct($href_value, $type_value, $rel_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$HREFATTR] = $href_value;
        $this->attributes[self::$TYPEATTR] = $type_value;
        $this->attributes[self::$RELATTR] = $rel_value;

        $this->children = NULL;
    }

    public function set_hyperlink($reference_url) {
        $this->attriubutes[self::$HREFATTR] = $reference_url;
    }

    public function set_linktype($value) {
        $this->attributes[self::$TYPEATTR] = $value;
    }

    public function set_relationship($type) {
        $this->attributes[self::$RELATTR] = $type;
    }
}

class PElement extends HtmlMarkup
{
    public static $TAGNAME = "p";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $text = new TextElement($content_string);     
        array_push($this->children, $text);
    }

    public function push_text($content_string) {
        $text = new TextElement($content_string);
        array_push($this->children, $text);
    }

    public function push_break() {
        $break = new BrElement();
        array_push($this->children, $break);
    }
}

class ScriptElement extends HtmlMarkup
{
    public static $TAGNAME = "script";

    public static $SRCATTR = "src";

    public function __construct($script_url) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$SRCATTR] = $script_url;

        $this->children = array();
    }

    public function set_source($script_url) {
        $this->attributes[self::$SRCATTR] = $script_url;
    }
}

class SpanElement extends HtmlMarkup
{
    public static $TAGNAME = "span";

    public function __construct() { 
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();
    }
}

class TextareaElement extends HtmlMarkup
{
    public static $TAGNAME = "textarea";

    public function __construct(FormElement $parent, $name, $init_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $initial_text = new TextElement($init_value);
        array_push($this->children, $initial_text);
    }

    // TODO: figure out how to comment on this....
    // need to render this in the form of <textarea>formatted_text</textarea>
    public function render($indent_unit, $indent_level) {
        $original_output = parent::render($indent_unit, $indent_level);
        $closetag = parent::$PAIREDCLOSING . $this->name . parent::$CLOSING;
        $closetag_position = strpos($original_output, $closetag);

        // TODO: remove linebreak and spaces before and after child content
        $output = $original_output;

        return $output;
    }
}

class TitleElement extends HtmlMarkup
{
    public static $TAGNAME = "title";

    public function __construct(HeadElement $parent, $title_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $content_element = new TextElement($title_string);
        array_push($this->children, $content_element);
    }
}
?>