<?php
interface Renderable
{
    public function render($indent_unit, $indent_level);
}

interface Labelable
{
    public function label_first();
}

class TextElement implements Renderable
{
    public $content;

    public function __construct($text_content) {
        $this->content = $text_content;
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
        $value = $value_string;
        $this->attributes[self::$IDATTR] = $value;
    }

    public function add_class($value_string) {
        $value = $value_string;
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

    public function push_text_input($name, $value, $label_text) {
        $input = new TextInputElement($this, $name, $value);
        $label = new LabelElement($input, $label_text);
        array_push($this->children, $label);
        return $input;
    }

    public function push_password_input($name, $value, $label_text) {
        $input = new PasswordInputElement($this, $name, $value);
        $label = new LabelElement($input, $label_text);
        array_push($this->children, $label);
        return $input;
    }

    public function push_radio_input($name, $value, $label_text) {
        $input = new RadioInputElement($this, $name, $value);
        $label = new LabelElement($input, $label_text);
        array_push($this->children, $label);
        return $input;
    }

    public function push_checkbox_input($name, $value, $label_text) {
        $input = new CheckboxInputElement($this, $name, $value);
        $label = new LabelElement($input, $label_text);
        array_push($this->children, $label);
        return $input;
    }

    public function push_select($name, $label_text,
                                $first_value, $first_text,
                                $second_value, $second_text) {
        $selection = new SelectElement($this, $name,
                                       $first_value, $first_text,
                                       $second_value, $second_text);
        $label = new LabelElement($selection, $label_text);
        array_push($this->children, $label);
        return $selection;
    }

    public function push_textarea($name, $value, $label_text) {
        $area = new TextareaElement($this, $name, $value);
        $label = new LabelElement($area, $label_text);
        array_push($this->children, $label);
        return $area;
    }
}

class CheckboxInputElement extends HtmlMarkup implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "checkbox";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $CHECKATTR = "checked";

    public static $CHECKEDVALUE = "checked";

    public function __construct(FormElement $host, $name, $value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $value;
        $this->attributes[self::$CHECKATTR] = "";        

        $this->children = NULL;
    }

    public function label_first() {
        return FALSE;
    }

    public function check() {
        $this->attributes[self::$CHECKATTR] = self::$CHECKEDVALUE;
    }

    public function uncheck() {
        $this->attributes[self::$CHECKATTR] = "";
    }
}

class PasswordInputElement extends HtmlMarkup implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "password";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";
    
    public function __construct(FormElement $host, $name, $default_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $default_value;
        $this->attributes[self::$MAXLENGTHATTR] = "";

        $this->children = NULL;
    }

    public function label_first() {
        return TRUE;
    }    

    public function set_maxlength($integer_value) {
        $this->attriubutes[self::$MAXLENGTHATTR] = $integer_value;
    }
}

class RadioInputElement extends HtmlMarkup implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "radio";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $CHECKATTR = "checked";

    public static $CHECKEDVALUE = "checked";

    public function __construct(FormElement $host, $name, $value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $value;
        $this->attributes[self::$CHECKATTR] = "";

        $this->children = NULL;
    }

    public function label_first() {
        return FALSE;
    }

    public function check() {
        $this->attributes[self::$CHECKATTR] = self::$CHECKEDVALUE;
    }

    public function uncheck() {
        $this->attributes[self::$CHECKATTR] = "";
    }
}

class TextInputElement extends HtmlMarkup implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "text";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";

    public function __construct(FormElement $host, $name, $default_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $default_value;
        $this->attributes[self::$MAXLENGTHATTR] = "";        

        $this->children = NULL;
    }

    public function label_first() {
        return TRUE;
    }    

    public function set_maxlength($integer_value) {
        $this->attributes[self::$MAXLENGTHATTR] = $integer_value;
    }
}

class OptionElement extends HtmlMarkup
{
    public static $TAGNAME = "option";

    public static $VALUEATTR = "value";
    public static $SELECTATTR = "selected";

    public static $SELECTEDVALUE = "selected";

    public function __construct(FormElement $host, $value, $option_text) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$VALUEATTR] = $value;
        $this->attributes[self::$SELECTATTR] = "";

        $this->children = array();
        $text = new TextElement($option_text);
        array_push($this->children, $text);
    }

    public function select() {
        $this->attributes[self::$SELECTATTR] = self::$SELECTEDVALUE;
    }
}

class SelectElement extends HtmlMarkup implements Labelable
{
    public static $TAGNAME = "select";

    public static $NAMEATTR = "";

    public function __construct(FormElement $host, $name,
                                $value_first, $text_first,
                                $value_second, $text_second) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$NAMEATTR] = $name;
        
        $this->children = array();
        $option_first = new OptionElement($host, $value_first, $text_first);
        $option_second = new OptionElement($host, $value_second, $text_second);
        array_push($this->children, $option_first);
        array_push($this->children, $option_second);
    }

    public function label_first() {
        return TRUE;
    }

    public function select_default($option_index) {
        $default_selection = NULL;
        
        try {
            $default_selection = $this->children[$option_index];
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $default_selection->select();
    }
}

class TextareaElement extends HtmlMarkup implements Labelable
{
    public static $TAGNAME = "textarea";

    public function __construct(FormElement $host, $name, $init_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $initial_text = new TextElement($init_value);
        array_push($this->children, $initial_text);
    }

    // children of textarea has to be rendered without indent
    // because it will be accounted for layout of its content
    public function render($indent_unit, $indent_level) {
        $unindented_output = parent::render("", 0);
        return $unindented_output;
    }

    public function label_first() {
        return TRUE;
    }
}

class LabelElement extends HtmlMarkup
{
    public static $TAGNAME = "label";

    public function __construct(Labelable $input, $text) {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();

        $label_text = new TextElement($text);

        if ($input->label_first()) {
            array_push($this->children, $label_text);
            array_push($this->children, $input);
        } else {
            array_push($this->children, $input);
            array_push($this->children, $label_text);
        }
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
        $encoded_text = $info_text;
        $this->attributes[self::$ALTATTR] = $encoded_text;
    }

    public function set_title($text) {
        $encoded_text = $text;
        $this->attriubutes[self::$TITLEATTR] = $encoded_text;
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