<?php
require 'html_attributes.php';

interface Renderable
{
    const TEXT = "schema for text element";
    const SINGLE = "schema for empty html element";
    const PAIRED = "schema of paired html element";

    public function name();
    public function schema();
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
    public static $NAME = "#";

    public $content;

    public function __construct($text_content) {
        $this->content = $text_content;
    }

    public function name() {
        return TextElement::$NAME;
    }

    public function schema() {
        return Renderable::TEXT;
    }
}

class AElement implements Renderable
{
    public static $NAME = "a";

    public static $HREF = "href";
    public static $TARGET = "target";

    private $attributes;

    public $children;

    public function __construct($href_value, $link_text) {
        $valid_attributes = array(AElement::$HREF, AElement::$TARGET);
        $this->attributes = new HtmlAttributes($valid_attributes);

        $this->attributes->set(AElement::$HREF, $href_value);

        $content_element = new TextElement($link_text);
        $this->children = new HtmlChildren();
        $this->children->add($content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return AElement::$NAME;
    }
}

class BodyElement implements Renderable
{
    public static $NAME = "body";

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
        return BodyElement::$NAME;
    }
}

class BrElement implements Renderable
{
    public static $NAME = "br";

    public $attributes;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
    }

    public function schema() {
        return Renderable::SINGLE;
    }

    public function name() {
        return BrElement::$NAME;
    }
}

class DivElement implements Renderable
{
    public static $NAME = "div";

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
        return DivElement::$NAME;
    }
}

class FormElement implements Renderable
{
    public static $NAME = "form";

    public static $ACTION = "action";
    public static $METHOD = "method";

    private $attributes;

    public $children;

    public function __construct($handler_url) {
        $valid_attributes = array(FormElement::$ACTION, FormElement::$METHOD);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(FormElement::$ACTION, $handler_url);

        $this->children = new HtmlChildren();        
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return FormElement::$NAME;
    }

    public function push($input_type, $input_name) {
        $input = NULL;

        if ($input_type == InputTextElement::$INPUT) {
            $input = new InputTextElement($this, $input_name);            
        } else {
            echo "TODO FormElement";
        }

        $this->children->add($input);
    }
}

class H1Element implements Renderable
{
    public static $NAME = "h1";

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
        return H1Element::$NAME;
    }
}

class H2Element implements Renderable
{
    public static $NAME = "h2";

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
        return H2Element::$NAME;
    }
}

class H3Element implements Renderable
{
    public static $NAME = "h3";

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
        return H3Element::$NAME;
    }
}

class H4Element implements Renderable
{
    public static $NAME = "h4";

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
        return H4Element::$NAME;
    }
}

class H5Element implements Renderable
{
    public static $NAME = "h5";

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
        return H5Element::$NAME;
    }
}

class H6Element implements Renderable
{
    public static $NAME = "h6";

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
        return H6Element::$NAME;
    }
}

class HeadElement implements Renderable
{
    public static $NAME = "head";

    private $attributes;

    public $children;

    public function __construct($title_text) {
        $this->attributes = new HtmlAttributes(array());

        $charset_meta = new MetaElement("UTF-8");
        $title = new TitleElement($title_text);      

        $this->children = new HtmlChildren();
        $this->children->add($charset_meta);
        $this->children->add($title);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return HeadElement::$NAME;
    }
}

class HrElement implements Renderable
{
    public static $NAME = "hr";

    private $attributes;

    public function __construct() {
        $this->attributes = new HtmlAttributes(array());
    }

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return HrElement::$NAME;
    }
}

class HtmlElement implements Renderable
{
    public static $NAME = "html";

    private $attributes;

    public $children;

    public function __construct(HeadElement $head, BodyElement $body) {
        $this->attributes = new HtmlAttributes(array());

        $this->children = new HtmlChildren();
        $this->children->add($head);
        $this->children->add($body);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return HtmlElement::$NAME;
    }
}

class ImgElement implements Renderable
{
    public static $NAME = "img";

    public static $SRC = "src";
    public static $ALT = "alt";
    public static $TITLE = "title";

    private $attributes;

    public function __construct($src_value, $alt_value) {
        $valid_attributes = array(ImgElement::$SRC, ImgElement::$ALT,
                                  ImgElement::$TITLE);
        $this->attributes = new HtmlAttributes($valid_attributes);

        $src_attribute = new SrcAttribute($src_value);
        $alt_attribute = new AltAttribute($alt_value);        

        $this->attributes->add($src_attribute);
        $this->attributes->add($alt_attribute);
    }

    public function name() {
        return ImgElement::$NAME;
    }

    public function schema() {
        return Renderable::SINGLE;
    }
}

class InputTextElement implements Renderable
{
    public static $NAME = "input";
    public static $INPUT = "text";

    public static $TYPE = "type";
    public static $NAME = "name";
    public static $MAXLENGTH = "maxlength";

    private $attributes;

    // this should never be called explicitly. use FormElement->push() instead
    public function __construct(FormElement $form, $name_value) {
        $valid_attributes = array(InputTextElement::$TYPE,
                                  InputTextElement::$NAME,
                                  InputTextElement::$MAXLENGTH);

        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(InputTextElement::$TYPE,
                               InputTextElement::$INPUT);
        $this->attributes->set(InputTextElement::$NAME, $name_value);
    }

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return InputElement::$NAME;
    }
}

class LinkElement implements Renderable
{
    public static $NAME = "link";

    public static $HREF = "href";
    public static $TYPE = "type";
    public static $REL = "rel";

    private $attributes;

    public function __construct($href_value, $type_value, $rel_value) {
        $valid_attributes = array(LinkElement::$HREF,
                                  LinkElement::$TYPE,
                                  LinkElement::$REL);
        $this->attributes = new HtmlAttributes($valid_attributes);

        $this->attributes->set(LinkElement::$HREF, $href_value);
        $this->attributes->set(LinkElement::$TYPE, $type_value);
        $this->attributes->set(LinkElement::$REL, $rel_value); 
    }

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return LinkElement::$NAME;
    }
}

class MetaElement implements Renderable
{
    public static $NAME = "meta";

    public static $CHARSET = "charset";

    private $attributes;

    public function __construct($charset_value) {
        $valid_attributes = array(MetaElement::$CHARSET);
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set($charset_value);
    }

    public function schema() {
        return Renderable::SINGLE;
    }    

    public function name() {
        return MetaElement::$NAME;
    }
}

class PElement implements Renderable
{
    public static $NAME = "p";

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
        return PElement::$NAME;
    }
}

class ScriptElement implements Renderable
{
    public static $NAME = "script";

    public static $SRC = "src";

    private $attributes;

    public $children;

    public function __construct($src_value) {
        $valid_attributes = array("arc");
        $this->attributes = new HtmlAttributes($valid_attributes);
        $this->attributes->set(ScriptElement::$SRC, $src_value);        

        $this->children = new HtmlChildren();
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return ScriptElement::$NAME;
    }
}

class SpanElement implements Renderable
{
    public static $NAME = "span";

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
        return SpanElement::$NAME;
    }
}

class TitleElement implements Renderable
{
    public static $NAME = "title";

    private $attributes;

    public $children;

    public function __construct($title_value) {
        $this->attributes = new HtmlAttributes(array());

        $content_element = new TextElement($title_value);
        $this->children = new HtmlChildren();        
        $this->children->add($content_element);
    }

    public function schema() {
        return Renderable::PAIRED;
    }    

    public function name() {
        return TitleElement::$NAME;
    }
}
?>