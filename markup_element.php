<?php
require 'markup_attributes.php';

interface Composable
{
    public function compose($indent_unit, $indent_level);
}

class HtmlElement implements Composable
{
    public static $empty_tag_names = array("br", "hr", "meta", "link");

    private $tag_name;
    private $children;

    public $attributes;    

    public function __construct($name) {
        $this->tag_name = $name;
        $this->attributes = new MarkupAttributes();
        $this->children = array();
    }

    public function add_child(Composable $element) {
        array_push($this->children, $element);
    }

    public function compose($indent_unit, $indent_level) {
        $result = "";
        $indent = str_repeat($indent_unit, $indent_level);

        $opening = $indent . "<" . $this->tag_name;

        $attribute = $this->attributes->serialize();

        if ($attribute != "") {
            $attribute = " " . $attribute;
        }

        $closing = "";

        if (in_array($this->tag_name, HtmlElement::$empty_tag_names)) {
            $closing = " />";
        } else if (count($this->children) == 0) {
            $closing = ">\n";
            $closing .= $indent . "</" . $this->tag_name;
            $closing .= ">";
        } else if (count($this->children) > 0) {
            $closing .= ">\n";

            $child_level = $indent_level + 1;
            $child_markup = "";
            foreach ($this->children as $element) {
                $child_markup .= $element->compose($indent_unit, $child_level);
            }

            $closing .= $child_markup;
            $closing .= $indent . "</" . $this->tag_name . ">\n";
        } else {
            $closing = ": error closing markup>";
        }

        $result = $opening . $attribute . $closing . "\n";

        return $result;
    }
}

class TextElement implements Composable
{
    private $content;

    public function __construct($text_content) {
        $this->content = $text_content;
    }

    public function compose($indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);
        $result = $indent . $this->content . "\n";

        return $result;
    }
}
?>