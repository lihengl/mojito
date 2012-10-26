<?php
require 'html_attributes.php';

interface Renderable
{
    const TEXT = "schema for text element";
    const SINGLE = "schema for empty html element";
    const PAIRED = "schema of paired html element";

    public function schema();
    public function name();
    public function attributes();
    public function children();
}

class TextElement implements Renderable
{
    public $content;

    public function __construct($text_content) {
        $this->content = htmlentities($text_content);
    }

    public function schema() {
        return Renderable::TEXT;
    }    

    public function name() {
        echo "[TextElement] Error: tried to get tag name from TextElement";
        return "";
    }

    public function attributes() {
        echo "[TextElement] Error: tried to get attributes from TextElement";
        return "";
    }

    public function children() {
        echo "[TextElement] Error: tried to get children from TextElement";
        return "";
    }
}

class AElement implements Renderable
{
    public static $TAGNAME = "a";

    public static $HREFATTR = "href";
    public static $TARGETATTR = "target";

    private $attributes;
    private $children;

    public function __construct($href_value, $link_text) {
        $valid_attributes = array(self::$HREFATTR, self::$TARGETATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$HREFATTR, $href_value);

        $content_element = new TextElement($link_text);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }
}

class BodyElement implements Renderable
{
    public static $TAGNAME = "body";

    private $attributes;
    private $children;

    public function __construct(HtmlElement $parent) {
        $this->attributes = new HtmlAttributes(array());
        $this->children = array();
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
        return $element;
    }

    public function children() {
        return $this->children;
    }    
}

class BrElement implements Renderable
{
    public static $TAGNAME = "br";

    private $attributes;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
    }

    public function schema() {
        return Renderable::SINGLE;
    }

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        echo "[BrElement] Error: tried to get children from br element";
        return "";
    }
}

class DivElement implements Renderable
{
    public static $TAGNAME = "div";

    private $attributes;
    private $children;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
        $this->children = array();
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
        return $element;
    }

    public function children() {
        return $this->children;
    }    
}

class FormElement implements Renderable
{
    public static $TAGNAME = "form";

    public static $ACTIONATTR = "action";
    public static $METHODATTR = "method";

    private $attributes;
    private $children;

    public function __construct($handler_url) {
        $valid_attributes = array(self::$ACTIONATTR, self::$METHODATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$ACTIONATTR, $handler_url);

        $this->children = array();        
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
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

class H1Element implements Renderable
{
    public static $TAGNAME = "h1";

    private $attributes;
    private $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_text = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_text);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

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

class H2Element implements Renderable
{
    public static $TAGNAME = "h2";

    private $attributes;
    private $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class H3Element implements Renderable
{
    public static $TAGNAME = "h3";

    private $attributes;
    private $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();        
        array_push($this->children, $content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class H4Element implements Renderable
{
    public static $TAGNAME = "h4";

    private $attributes;
    private $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class H5Element implements Renderable
{
    public static $TAGNAME = "h5";

    private $attributes;
    private $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();        
        array_push($this->children, $content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class H6Element implements Renderable
{
    public static $TAGNAME = "h6";

    private $attributes;
    private $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = array();
        array_push($this->children, $content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class HeadElement implements Renderable
{
    public static $TAGNAME = "head";

    private $attributes;
    private $children;

    public function __construct(HtmlElement $parent, $charset, $title) {
        $this->attributes = new HtmlAttributes(array());

        $charset = new MetaCharsetElement($this, $charset);
        $title = new TitleElement($this, $title);

        $this->children = array();
        array_push($this->children, $charset);
        array_push($this->children, $title);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class HrElement implements Renderable
{
    public static $TAGNAME = "hr";

    private $attributes;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
    }

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        echo "[HrElement] Error: tried to get children from hr element";
        return "";
    }    
}

class HtmlElement implements Renderable
{   
    public static $TAGNAME = "html";

    public static $CHARSET = "UTF-8";    

    private $attributes;
    private $children;

    private $head;
    private $body;

    public function __construct($title_text) {
        $this->attributes = new HtmlAttributes(array());

        $this->head = new HeadElement($this, self::$CHARSET, $title_text);
        $this->body = new BodyElement($this);

        $this->children = array();
        array_push($this->children, $this->head);
        array_push($this->children, $this->body);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    

    public function head() {
        return $this->head;
    }

    public function body() {
        return $this->body;
    }
}

class ImgElement implements Renderable
{
    public static $TAGNAME = "img";

    public static $SRCATTR = "src";
    public static $ALTATTR = "alt";
    public static $TITLEATTR = "title";

    private $attributes;

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

    public function schema() {
        return Renderable::SINGLE;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        echo "[ImgElement] Error: tried to get children from img element";
        return ""; 
    }    
}

class InputTextElement implements Renderable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "text";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";

    private $attributes;

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

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        echo "[InputTextElement] Error: tried to get children from input element";
        return "";
    }
}

class LinkElement implements Renderable
{
    public static $TAGNAME = "link";

    public static $HREFATTR = "href";
    public static $TYPEATTR = "type";
    public static $RELATTR = "rel";

    private $attributes;

    public function __construct($href_value, $type_value, $rel_value) {
        $valid_attributes = array(self::$HREFATTR,
                                  self::$TYPEATTR,
                                  self::$RELATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);

        $this->attributes->set(self::$HREFATTR, $href_value);
        $this->attributes->set(self::$TYPEATTR, $type_value);
        $this->attributes->set(self::$RELATTR, $rel_value); 
    }

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        echo "[LinkElement] Error: tried to get children from link element";
    }
}

class MetaCharsetElement implements Renderable
{
    public static $TAGNAME = "meta";

    public static $CHARSETATTR = "charset";

    private $attributes;

    public function __construct(HeadElement $parent, $charset_value) {
        $valid_attributes = array(self::$CHARSETATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$CHARSETATTR, $charset_value);
    }

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        echo "[MetaCharsetElement] Error: tried to get children from meta element";
        return "";
    }
}

class PElement implements Renderable
{
    public static $TAGNAME = "p";

    private $attributes;
    private $children;

    public function __construct($text_content) {
        $this->attributes = new HtmlAttributes(array());

        $text = new TextElement($initial_text_content);
        $this->children = array();        
        array_push($this->children, $text);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    

    public function push_text(TextElement $text) {
        array_push($this->children, $text);
    }

    public function push_break(BrElement $break) {
        array_push($this->children, $break);
    }
}

class ScriptElement implements Renderable
{
    public static $TAGNAME = "script";

    public static $SRCATTR = "src";

    private $attributes;
    private $children;

    public function __construct($script_url) {
        $valid_attributes = array(self::$SRCATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$SRCATTR, $script_url);        

        $this->children = array();
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class SpanElement implements Renderable
{
    public static $TAGNAME = "span";

    private $attributes;
    private $children;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
        $this->children = array();
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}

class TitleElement implements Renderable
{
    public static $TAGNAME = "title";

    private $attributes;
    private $children;

    public function __construct(HeadElement $parent, $title_value) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($title_value);
        $this->children = array();        
        array_push($this->children, $content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return self::$TAGNAME;
    }

    public function attributes() {
        return $this->attributes->all_specified();
    }

    public function children() {
        return $this->children;
    }    
}
?>