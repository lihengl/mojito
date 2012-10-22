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

    public function get_all() {
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
?>