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
    public static $TAG_NAME = "#text";

    public $content;

    public function __construct($text_content) {
        $this->content = $text_content;
    }

    public function name() {
        return TextElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::TEXT_SCHEMA;
    }
}

class BodyElement implements Composable
{
    public static $TAG_NAME = "body";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return BodyElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class BrElement implements Composable
{
    public static $TAG_NAME = "br";

    public $attributes;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
    }

    public function name() {
        return BrElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::EMPTY_SCHEMA;
    }
}

class HeadElement implements Composable
{
    public static $TAG_NAME = "head";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return HeadElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class H1Element implements Composable
{
    public static $TAG_NAME = "h1";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H1Element::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class H2Element implements Composable
{
    public static $TAG_NAME = "h2";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H2Element::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class H3Element implements Composable
{
    public static $TAG_NAME = "h3";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H3Element::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class H4Element implements Composable
{
    public static $TAG_NAME = "h4";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H4Element::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class H5Element implements Composable
{
    public static $TAG_NAME = "h5";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H5Element::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class H6Element implements Composable
{
    public static $TAG_NAME = "h6";

    public $attributes;
    public $children;

    public function __construct($content) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($content);
        $this->children->add($content_element);
    }

    public function name() {
        return H6Element::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class HtmlElement implements Composable
{
    public static $TAG_NAME = "html";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return HtmlElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class HrElement implements Composable
{
    public static $TAG_NAME = "hr";

    public $attributes;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
    }

    public function name() {
        return HrElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::EMPTY_SCHEMA;
    }
}

class ImgElement implements Composable
{
    public static $TAG_NAME = "img";
    public static $SRC_NAME = "src";
    public static $ALT_NAME = "alt";

    public $attributes;

    public function __construct($src_value, $alt_value) {
        $this->attributes = new MarkupAttributes();
        $this->attributes->others->set(ImgElement::$SRC_NAME, $src_value);
        $this->attributes->others->set(ImgElement::$ALT_NAME, $alt_value);        
    }

    public function name() {
        return ImgElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::EMPTY_SCHEMA;
    }
}

class LinkElement implements Composable
{
    public static $TAG_NAME = "link";
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
        return LinkElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::EMPTY_SCHEMA;
    }
}

class MetaElement implements Composable
{
    public static $TAG_NAME = "meta";

    public $attributes;

    public function __construct($name, $value) {
        $this->attributes = new MarkupAttributes();
        $this->attributes->others->set($name, $value);
    }

    public function name() {
        return MetaElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::EMPTY_SCHEMA;
    }
}

class PElement implements Composable
{
    public static $TAG_NAME = "p";

    public $attributes;
    public $children;

    public function __construct() {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();
    }

    public function name() {
        return PElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class ScriptElement implements Composable
{
    public static $TAG_NAME = "script";
    public static $SRC_NAME = "src";

    public $attributes;
    public $children;

    public function __construct($src_value) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $this->attributes->others->set(ScriptElement::$SRC_NAME, $src_value);
    }

    public function name() {
        return ScriptElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}

class TitleElement implements Composable
{
    public static $TAG_NAME = "title";

    public $attributes;
    public $children;

    public function __construct($title_value) {
        $this->attributes = new MarkupAttributes();
        $this->children = new ElementChildren();

        $content_element = new TextElement($title_value);
        $this->children->add($content_element);
    }

    public function name() {
        return TitleElement::$TAG_NAME;
    }

    public function schema() {
        return Composable::PAIRED_SCHEMA;
    }
}
?>