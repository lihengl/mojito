<?php
class EmptyElement
{
    private $tag_name;

    public $indent_level;
    public $attributes;

    public function __construct($name) {
        $this->tag_name = $name;

        $this->indent_level = 0;
        $this->attributes = array();
    }

    public function compose() {
        $html = str_repeat("    ", $this->indent_level);
        $html = $html . "<" . $this->tag_name;

        foreach ($this->attributes as $name => $value) {
            $html = $html . " ";
            $value_string = implode(" ", $value);
            $html = $html . $name . '="' . $value_string . '"';
        }  

        $html = $html . " />\n";
        return $html;
    }
}
?>