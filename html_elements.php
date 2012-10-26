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
}

class HtmlChildren
{
    private $elements;

    public function __construct() {
        $this->elements = array();
    }

    public function add(Renderable $element) {
        array_push($this->elements, $element);
    }

    public function all() {
        return $this->elements;
    }
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
}

class AElement implements Renderable
{
    public static $TAGNAME = "a";

    public static $HREFATTR = "href";
    public static $TARGETATTR = "target";

    private $attributes;
    public $children;

    public function __construct($href_value, $link_text) {
        $valid_attributes = array(self::$HREFATTR, self::$TARGETATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);

        $this->attributes->set(self::$HREFATTR, $href_value);

        $content_element = new TextElement($link_text);
        $this->children = new HtmlChildren();
        $this->children->add($content_element);
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
}

class BodyElement implements Renderable
{
    public static $TAGNAME = "body";

    private $attributes;
    public $children;

    public function __construct(HtmlElement $parent) {
        $this->attributes = new HtmlAttributes(array());

        $this->children = new HtmlChildren();
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
}

class DivElement implements Renderable
{
    public static $TAGNAME = "div";

    private $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
        $this->children = new HtmlChildren();
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
}

class FormElement implements Renderable
{
    public static $TAGNAME = "form";

    public static $ACTIONATTR = "action";
    public static $METHODATTR = "method";

    private $attributes;
    public $children;

    public function __construct($handler_url) {
        $valid_attributes = array(self::$ACTIONATTR, self::$METHODATTR);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$ACTIONATTR, $handler_url);

        $this->children = new HtmlChildren();        
    }

    public function push($input_type, $input_name) {
        $input = NULL;

        if ($input_type == InputTextElement::$TYPENAME) {
            $input = new InputTextElement($this, $input_name);            
        } else {
            echo "TODO FormElement";
        }

        $this->children->add($input);
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
}

class H1Element implements Renderable
{
    public static $TAGNAME = "h1";

    private $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_text = new TextElement($content);
        $this->children = new HtmlChildren();
        $this->children->add($content_text);
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
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = new HtmlChildren();        
        $this->children->add($content_element);
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
}

class H3Element implements Renderable
{
    public static $TAGNAME = "h3";

    private $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = new HtmlChildren();        
        $this->children->add($content_element);
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
}

class H4Element implements Renderable
{
    public static $TAGNAME = "h4";

    private $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = new HtmlChildren();        
        $this->children->add($content_element);
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
}

class H5Element implements Renderable
{
    public static $TAGNAME = "h5";

    private $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = new HtmlChildren();        
        $this->children->add($content_element);
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
}

class H6Element implements Renderable
{
    public static $TAGNAME = "h6";

    private $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($content);
        $this->children = new HtmlChildren();        
        $this->children->add($content_element);
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
}

class HeadElement implements Renderable
{
    public static $TAGNAME = "head";

    private $attributes;
    public $children;

    public function __construct(HtmlElement $parent, $charset, $title) {
        $this->attributes = new HtmlAttributes(array());
        $this->children = new HtmlChildren();        

        $charset = new MetaCharsetElement($this, $charset);
        $title = new TitleElement($this, $title);

        $this->children->add($charset);
        $this->children->add($title);
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
}

class HtmlElement implements Renderable
{   
    public static $TAGNAME = "html";

    public static $CHARSET = "UTF-8";    

    private $attributes;
    public $children;

    private $head;
    private $body;

    public function __construct($title_text) {
        $this->attributes = new HtmlAttributes(array());
        $this->children = new HtmlChildren();

        $this->head = new HeadElement($this, self::$CHARSET, $title_text);
        $this->body = new BodyElement($this);

        $this->children->add($this->head);
        $this->children->add($this->body);
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
}

class PElement implements Renderable
{
    public static $TAGNAME = "p";

    private $attributes;
    public $children;

    public function __construct($text_content) {
        $this->attributes = new HtmlAttributes(array());

        $text = new TextElement($initial_text_content);
        $this->children = new HtmlChildren();        
        $this->children->add($text);
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
}

class ScriptElement implements Renderable
{
    public static $TAGNAME = "script";

    public static $SRCATTR = "src";

    private $attributes;
    public $children;

    public function __construct($src_value) {
        $valid_attributes = array("arc");
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(self::$SRCATTR, $src_value);        

        $this->children = new HtmlChildren();
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
}

class SpanElement implements Renderable
{
    public static $TAGNAME = "span";

    private $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
        $this->children = new HtmlChildren();
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
}

class TitleElement implements Renderable
{
    public static $TAGNAME = "title";

    private $attributes;
    public $children;

    public function __construct(HeadElement $parent, $title_value) {
        $this->attributes = new HtmlAttributes(array());
        $this->children = new HtmlChildren();        

        $content_element = new TextElement($title_value);
        $this->children->add($content_element);
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
}
?>