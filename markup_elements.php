<?php
require 'markup_attributes.php';
require 'markup_composer.php';

class ElementChildren
{
    private $elements;

    public function __construct() {
        $this->elements = array();
    }

    public function add(Composable $element) {
        array_push($this->elements, $element);
    }

    public function all() {
        return $this->elements;
    }
}

class TextElement implements Composable
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
        return Composable::TEXT_ELEMENT;
    }
}

class AElement implements Composable
{
    public static $NAME = "a";
    public static $HREF_NAME = "href";

    public $attributes;
    public $children;

    public function __construct($href_value, $link_text) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $this->attributes->others->set(AElement::$HREF_NAME, $href_value);

        $content_element = new TextElement($link_text);
        $this->children->add($content_element);
    }

    public function name() {
        return AElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class BodyElement implements Composable
{
    public static $NAME = "body";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return BodyElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class BrElement implements Composable
{
    public static $NAME = "br";

    public $attributes;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
    }

    public function name() {
        return BrElement::$NAME;
    }

    public function schema() {
        return Composable::EMPTY_ELEMENT;
    }
}

class DivElement implements Composable
{
    public static $NAME = "div";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return DivElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class HeadElement implements Composable
{
    public static $NAME = "head";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return HeadElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class H1Element implements Composable
{
    public static $NAME = "h1";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H1Element::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class H2Element implements Composable
{
    public static $NAME = "h2";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H2Element::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class H3Element implements Composable
{
    public static $NAME = "h3";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H3Element::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class H4Element implements Composable
{
    public static $NAME = "h4";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H4Element::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class H5Element implements Composable
{
    public static $NAME = "h5";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H5Element::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class H6Element implements Composable
{
    public static $NAME = "h6";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H6Element::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class HtmlElement implements Composable
{
    public static $NAME = "html";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return HtmlElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class HrElement implements Composable
{
    public static $NAME = "hr";

    public $attributes;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
    }

    public function name() {
        return HrElement::$NAME;
    }

    public function schema() {
        return Composable::EMPTY_ELEMENT;
    }
}

class ImgElement implements Composable
{
    public static $NAME = "img";
    public static $SRC_NAME = "src";
    public static $ALT_NAME = "alt";

    public $attributes;

    public function __construct($src_value, $alt_value) {
        $this->attributes = new MarkupAttributes();
        $this->attributes->others->set(ImgElement::$SRC_NAME, $src_value);
        $this->attributes->others->set(ImgElement::$ALT_NAME, $alt_value);        
    }

    public function name() {
        return ImgElement::$NAME;
    }

    public function schema() {
        return Composable::EMPTY_ELEMENT;
    }
}

class LinkElement implements Composable
{
    public static $NAME = "link";
    public static $HREF_NAME = "href";
    public static $TYPE_NAME = "type";
    public static $REL_NAME = "rel";        

    public $attributes;

    public function __construct($href_value, $type_value, $rel_value) {
        $this->attributes = new MarkupAttributes();
        $this->attributes->others->set(LinkElement::$HREF_NAME, $href_value);
        $this->attributes->others->set(LinkElement::$TYPE_NAME, $type_value);
        $this->attributes->others->set(LinkElement::$REL_NAME, $rel_value);
    }

    public function name() {
        return LinkElement::$NAME;
    }

    public function schema() {
        return Composable::EMPTY_ELEMENT;
    }
}

class MetaElement implements Composable
{
    public static $NAME = "meta";

    public $attributes;

    public function __construct($name, $value) {
        $this->attributes = new MarkupAttributes();
        $this->attributes->others->set($name, $value);
    }

    public function name() {
        return MetaElement::$NAME;
    }

    public function schema() {
        return Composable::EMPTY_ELEMENT;
    }
}

class PElement implements Composable
{
    public static $NAME = "p";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return PElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class SpanElement implements Composable
{
    public static $NAME = "span";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return SpanElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class ScriptElement implements Composable
{
    public static $NAME = "script";
    public static $SRC_NAME = "src";

    public $attributes;
    public $children;

    public function __construct($src_value) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $this->attributes->others->set(ScriptElement::$SRC_NAME, $src_value);
    }

    public function name() {
        return ScriptElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}

class TitleElement implements Composable
{
    public static $NAME = "title";

    public $attributes;
    public $children;

    public function __construct($title_value) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($title_value);
        $this->children->add($content_element);
    }

    public function name() {
        return TitleElement::$NAME;
    }

    public function schema() {
        return Composable::PAIRED_ELEMENT;
    }
}
?>