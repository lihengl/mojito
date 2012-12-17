<?php
interface Renderable
{
    public function name();
    public function compose($indent_unit, $indent_level);
}

class TextNode implements Renderable
{
    public $content;

    public function __construct($text_content) {
        $this->content = $text_content;
    }

    public function name() {
        return NULL;
    }

    public function compose($indent_unit, $indent_level) {
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

abstract class HtmlNode implements Renderable
{
    private static $tag_opening = "<";
    private static $tag_closing = ">";

    private static $empty_closing = " />";
    private static $paired_closing = "</";

    private static $attrval_opening = '="';
    private static $attrval_closing = '"';

    private static $id = "id";
    private static $class = "class";

    private static $separator_char = " ";

    protected $tagname;
    protected $attributes;
    protected $children;

    public function __construct($tagname) {
        $this->tagname = $tagname;
        $this->attributes = array(self::$id=>"", self::$class=>"");
    }

    private function compose_attributes() {
        $rendering_pieces = array();
        foreach ($this->attributes as $name=>$value) {
            $rendering_piece = "";

            if ($value != "") {
                $rendering_piece = $name . self::$attrval_opening . $value . self::$attrval_closing;
                array_push($rendering_pieces, $rendering_piece);
            } else {
                $rendering_piece = "";
            }
        }
        $attribute_rendering = implode(self::$separator_char, $rendering_pieces);
        return $attribute_rendering;
    }

    private function compose_empty($opening, $indent) {
        $rendering = $opening . self::$empty_closing;
        $formatted = $indent . $rendering . "\n";
        return $formatted;
    }

    private function compose_paired($opening, $indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);

        $openline = $opening . self::$tag_closing;

        $midlines = "";
        $child_level = $indent_level + 1;

        foreach ($this->children as $child) {
            $midlines .= $child->compose($indent_unit, $child_level);
        }

        $closeline = self::$paired_closing . $this->tagname . self::$tag_closing;

        $formatted_open = $indent . $openline . "\n";
        $formatted_mid = $midlines;
        $formatted_close = $indent . $closeline . "\n";

        $formatted = $formatted_open . $formatted_mid . $formatted_close;
        return $formatted;
    }

    public function name() {
        return $this->tagname;
    }

    public function compose($indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);

        $opening = self::$tag_opening . $this->tagname;
        
        $attribute = $this->compose_attributes();

        if ($attribute != "") {
            $opening .= self::$separator_char . $attribute;
        } else {
            // attribute is empty and has nothing te be put into opening
            $opening = $opening; 
        }

        $output = "";

        if ($this->children === NULL) {
            $output = $this->compose_empty($opening, $indent);
        } else {
            // shorten variable names to follow the 80-column rule
            $unit =     $indent_unit;
            $level = $indent_level;
            $output = $this->compose_paired($opening, $unit, $level);
        }

        return $output;
    }

    public function classes($value = NULL) {
        $existing = $this->attributes[self::$class];

        if ($value === NULL) {
            $values = explode(self::$separator_char, $existing);
            return $values;
        } else if (strpos($value, self::$separator_char)) {
            return "";
        } else if ($existing === "") {
            $this->attributes[self::$class] = $value;
            return $value;
        } else if (strpos($existing, $value)) {
            return "";
        } else {
            $adding = $existing . self::$separator_char . $value;
            $this->attributes[self::$class] = $adding;
            return $value;
        }
    }

    public function attribute($name, $value = NULL) {
        if ($value === NULL) {
            return $this->attributes[$name];
        } else {
            $this->attributes[$name] = $value;
            return $value;
        }
    }

    public function id($value = NULL) {
        return $this->attribute(self::$id, $value);
    }
}
?>