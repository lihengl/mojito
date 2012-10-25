<?php
require 'html_attributes.php';

interface Renderable
{
    const TEXT_ELEMENT_SCHEMA = "schema for text element";
    const EMPTY_ELEMENT_SCHEMA = "schema for empty html element";
    const PAIRED_ELEMENT_SCHEMA = "schema of paired html element";

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
        return Renderable::TEXT_ELEMENT_SCHEMA;
    }
}

class AElement implements Renderable
{
    public static $NAME = "a";

    public $attributes;
    public $children;

    public function __construct($href_value, $link_text) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $href_attribute = new HrefAttribute($href_value);
        $this->attributes->add($href_attribute);

        $content_element = new TextElement($link_text);
        $this->children->add($content_element);
    }

    public function name() {
        return AElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class BodyElement implements Renderable
{
    public static $NAME = "body";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();
    }

    public function name() {
        return BodyElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class BrElement implements Renderable
{
    public static $NAME = "br";

    public $attributes;

    public function __construct() {
        $this->attributes = new HtmlAttributes();
    }

    public function name() {
        return BrElement::$NAME;
    }

    public function schema() {
        return Renderable::EMPTY_ELEMENT_SCHEMA;
    }
}

class DivElement implements Renderable
{
    public static $NAME = "div";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();
    }

    public function name() {
        return DivElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class FormElement implements Renderable
{
    public static $NAME = "form";

    public $attributes;
    public $children;

    public function __construct($action_handler) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $handler = new ActionAttribute($action_handler);
        $this->attributes->add($handler);
    }

    public function name() {
        return FormElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class H1Element implements Renderable
{
    public static $NAME = "h1";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H1Element::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class H2Element implements Renderable
{
    public static $NAME = "h2";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H2Element::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class H3Element implements Renderable
{
    public static $NAME = "h3";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H3Element::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class H4Element implements Renderable
{
    public static $NAME = "h4";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H4Element::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class H5Element implements Renderable
{
    public static $NAME = "h5";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H5Element::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class H6Element implements Renderable
{
    public static $NAME = "h6";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H6Element::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class HeadElement implements Renderable
{
    public static $NAME = "head";

    public $attributes;
    public $children;

    public function __construct($title_text) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $charset_attribute = new MetaAttribute("charset", "UTF-8");
        $charset_meta = new MetaElement($charset_attribute);
        $this->children->add($charset_meta);

        $title = new TitleElement($title_text);
        $this->children->add($title);
    }

    public function name() {
        return HeadElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class HrElement implements Renderable
{
    public static $NAME = "hr";

    public $attributes;

    public function __construct() {
        $this->attributes = new HtmlAttributes();
    }

    public function name() {
        return HrElement::$NAME;
    }

    public function schema() {
        return Renderable::EMPTY_ELEMENT_SCHEMA;
    }
}

class HtmlElement implements Renderable
{
    public static $NAME = "html";

    public $attributes;
    public $children;

    public function __construct(HeadElement $head, BodyElement $body) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $this->children->add($head);
        $this->children->add($body);
    }

    public function name() {
        return HtmlElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class ImgElement implements Renderable
{
    public static $NAME = "img";

    public $attributes;

    public function __construct($src_value, $alt_value) {
        $this->attributes = new HtmlAttributes();

        $src_attribute = new SrcAttribute($src_value);
        $alt_attribute = new AltAttribute($alt_value);        

        $this->attributes->add($src_attribute);
        $this->attributes->add($alt_attribute);
    }

    public function name() {
        return ImgElement::$NAME;
    }

    public function schema() {
        return Renderable::EMPTY_ELEMENT_SCHEMA;
    }
}

class InputElement implements Renderable
{
    public static $NAME = "input";

    public $attributes;

    public function __construct($type_value, $name_value) {
        $this->attributes = new HtmlAttributes();

        $type = new TypeAttribute($type_value);
        $name = new NameAttribute($name_value);

        $this->attributes->add($type);
        $this->attributes->add($name);        
    }

    public function name() {
        return InputElement::$NAME;
    }

    public function schema() {
        return Renderable::EMPTY_ELEMENT_SCHEMA;
    }
}

class LinkElement implements Renderable
{
    public static $NAME = "link";      

    public $attributes;

    public function __construct($href_value, $type_value, $rel_value) {
        $this->attributes = new HtmlAttributes();

        $href_attribute = new HrefAttribute($href_value);
        $type_attribute = new TypeAttribute($type_value);
        $rel_attribute = new RelAttribute($rel_value);

        $this->attributes->add($href_attribute);
        $this->attributes->add($type_attribute);
        $this->attributes->add($rel_attribute);        
    }

    public function name() {
        return LinkElement::$NAME;
    }

    public function schema() {
        return Renderable::EMPTY_ELEMENT_SCHEMA;
    }
}

class MetaElement implements Renderable
{
    public static $NAME = "meta";

    public $attributes;

    public function __construct(MetaAttribute $attribute) {
        $this->attributes = new HtmlAttributes();
        $this->attributes->add($attribute);
    }

    public function name() {
        return MetaElement::$NAME;
    }

    public function schema() {
        return Renderable::EMPTY_ELEMENT_SCHEMA;
    }
}

class PElement implements Renderable
{
    public static $NAME = "p";

    public $attributes;
    public $children;

    public function __construct($initial_text_content) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $text = new TextElement($initial_text_content);
        $this->children->add($text);
    }

    public function name() {
        return PElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class ScriptElement implements Renderable
{
    public static $NAME = "script";

    public $attributes;
    public $children;

    public function __construct($src_value) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $src_attribute = new SrcAttribute($src_value);
        $this->attributes->add($src_attribute);
    }

    public function name() {
        return ScriptElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class SpanElement implements Renderable
{
    public static $NAME = "span";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();
    }

    public function name() {
        return SpanElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}

class TitleElement implements Renderable
{
    public static $NAME = "title";

    public $attributes;
    public $children;

    public function __construct($title_value) {
        $this->attributes = new HtmlAttributes();
        $this->children = new HtmlChildren();

        $content_element = new TextElement($title_value);
        $this->children->add($content_element);
    }

    public function name() {
        return TitleElement::$NAME;
    }

    public function schema() {
        return Renderable::PAIRED_ELEMENT_SCHEMA;
    }
}
?>