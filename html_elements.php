<?php
require 'html_attributes.php';

interface Renderable
{
    public function name();
    public function attributes();
    public function children();
}

abstract class EmptyElement implements Renderable
{
    protected $attributes;

    abstract public function name();

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return NULL;
    }

    public function set_id($id_value) {
        $this->attributes->set_id($id_value);
    }

    public function set_classes($class_values) {
        $this->attributes->set_classes($class_values);
    }        
}

abstract class PairedElement implements Renderable
{
    protected $attributes;
    protected $children;

    abstract public function name();

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }

    public function set_id($id_value) {
        $this->attributes->set_id($id_value);
    }

    public function set_classes($class_values) {
        $this->attributes->set_classes($class_values);
    }    
}

class TextElement implements Renderable
{
    public $content;

    public function __construct($text_content) {
        $this->content = htmlentities($text_content);
    }

    public function name() {
        return NULL;
    }

    public function attributes() {
        return NULL;
    }

    public function children() {
        return NULL;
    }
}

class AElement extends PairedElement
{
    public static $TAGNAME = "a";

    public static $HREFATTR = "href";
    public static $TARGETATTR = "target";

    public function __construct($href_url, $link_text) {
        $valid_attributes = array(self::$HREFATTR, self::$TARGETATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$HREFATTR, $href_url);

        $content_element = new TextElement($link_text);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class BodyElement extends PairedElement
{
    public static $TAGNAME = "body";

    public function __construct(HtmlElement $parent) {
        $this->attributes = new HtmlAttributes(array());
        $this->children = array();
    }

    public function name() {
        return self::$TAGNAME;
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
        return $element;
    }
}

class BrElement extends EmptyElement
{
    public static $TAGNAME = "br";

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class DivElement extends PairedElement
{
    public static $TAGNAME = "div";

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
        $this->children = array();
    }

    public function name() {
        return self::$TAGNAME;
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
        return $element;
    }
}

class FormElement extends PairedElement
{
    public static $TAGNAME = "form";

    public static $ACTIONATTR = "action";
    public static $METHODATTR = "method";

    public function __construct($handler_url) {
        $valid_attributes = array(self::$ACTIONATTR, self::$METHODATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$ACTIONATTR, $handler_url);

        $this->children = array();        
    }

    public function name() {
        return self::$TAGNAME;
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
        return $element;
    }

    public function push_input($input_type, $input_name) {
        $input = NULL;

        if ($input_type == InputTextElement::$TYPENAME) {
            $input = new InputTextElement($this, $input_name);
            array_push($this->children, $input);            
        } else {
            echo "[FormElement] Error: unknown input type specified";
        }

        return $input;
    }        
}

class H1Element extends PairedElement
{
    public static $TAGNAME = "h1";

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_text = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_text);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class H2Element extends PairedElement
{
    public static $TAGNAME = "h2";

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function name() {
        return self::$TAGNAME;
    }   
}

class H3Element extends PairedElement
{
    public static $TAGNAME = "h3";

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();        
        array_push($this->children, $content_element);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class H4Element extends PairedElement
{
    public static $TAGNAME = "h4";

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class H5Element extends PairedElement
{
    public static $TAGNAME = "h5";

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();        
        array_push($this->children, $content_element);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class H6Element extends PairedElement
{
    public static $TAGNAME = "h6";

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class HeadElement extends PairedElement
{
    public static $TAGNAME = "head";

    public function __construct(HtmlElement $parent, $charset, $title) {
        $this->attributes = new HtmlAttributes(array());

        $charset = new MetaCharsetElement($this, $charset);
        $title = new TitleElement($this, $title);

        $this->children = array();
        array_push($this->children, $charset);
        array_push($this->children, $title);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class HrElement extends EmptyElement
{
    public static $TAGNAME = "hr";

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class HtmlElement extends PairedElement
{   
    public static $TAGNAME = "html";

    public static $CHARSET = "UTF-8";

    public $head;
    public $body;

    public function __construct($title_text) {
        $this->attributes = new HtmlAttributes(array());

        $this->head = new HeadElement($this, self::$CHARSET, $title_text);
        $this->body = new BodyElement($this);

        $this->children = array();
        array_push($this->children, $this->head);
        array_push($this->children, $this->body);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class ImgElement extends EmptyElement
{
    public static $TAGNAME = "img";

    public static $SRCATTR = "src";
    public static $ALTATTR = "alt";
    public static $TITLEATTR = "title";

    public function __construct($src_value, $alt_value) {
        $valid_attributes = array(self::$SRCATTR, self::$ALTATTR,
                                  self::$TITLEATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$SRCATTR, $src_value);
        $this->attributes->set(self::$ALTATTR, $alt_value);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class InputTextElement extends EmptyElement
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "text";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";

    // this should never be called explicitly. use FormElement->push() instead
    public function __construct(FormElement $form, $name_value) {
        $valid_attributes = array(self::$TYPEATTR,
                                  self::$NAMEATTR,
                                  self::$VALUEATTR,                                  
                                  self::$MAXLENGTHATTR);

        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$TYPEATTR,
                               self::$TYPENAME);
        $this->attributes->set(self::$NAMEATTR, $name_value);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class LinkElement extends EmptyElement
{
    public static $TAGNAME = "link";

    public static $HREFATTR = "href";
    public static $TYPEATTR = "type";
    public static $RELATTR = "rel";

    public function __construct($href_value, $type_value, $rel_value) {
        $valid_attributes = array(self::$HREFATTR,
                                  self::$TYPEATTR,
                                  self::$RELATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);

        $this->attributes->set(self::$HREFATTR, $href_value);
        $this->attributes->set(self::$TYPEATTR, $type_value);
        $this->attributes->set(self::$RELATTR, $rel_value); 
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class MetaCharsetElement extends EmptyElement
{
    public static $TAGNAME = "meta";

    public static $CHARSETATTR = "charset";

    public function __construct(HeadElement $parent, $charset_value) {
        $valid_attributes = array(self::$CHARSETATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$CHARSETATTR, $charset_value);
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class PElement extends PairedElement
{
    public static $TAGNAME = "p";

    public function __construct($text_content) {
        $this->attributes = new HtmlAttributes(array());

        $text = new TextElement($initial_text_content);
        $this->children = array();        
        array_push($this->children, $text);
    }

    public function name() {
        return self::$TAGNAME;
    }

    public function push_text(TextElement $text) {
        array_push($this->children, $text);
    }

    public function push_break(BrElement $break) {
        array_push($this->children, $break);
    }
}

class ScriptElement extends PairedElement
{
    public static $TAGNAME = "script";

    public static $SRCATTR = "src";

    public function __construct($script_url) {
        $valid_attributes = array(self::$SRCATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$SRCATTR, $script_url);        

        $this->children = array();
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class SpanElement extends PairedElement
{
    public static $TAGNAME = "span";

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
        $this->children = array();
    }

    public function name() {
        return self::$TAGNAME;
    }
}

class TitleElement extends PairedElement
{
    public static $TAGNAME = "title";

    public function __construct(HeadElement $parent, $title_value) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($title_value);
        $this->children = array();        
        array_push($this->children, $content_element);
    }

    public function name() {
        return self::$TAGNAME;
    }
}
?>