<?php
class EmptyElement
{
    private $tag_name;
    private $indent_level;

    public $id_attribute;
    public $class_attribute;    

    public function __construct($name, $level) {
        $this->tag_name = $name;
        $this->indent_level = $level;

        $this->id_attribute = "";
        $this->class_attribute = array();
    }

    public function compose() {
        $html = str_repeat("    ", $this->indent_level);
        $html = $html . "<" . $this->tag_name;
        
        if ($this->id_attribute != "") {
            $html = $html . ' id="' . $this->id_attribute . '"';
        } else {
            // no id specified, do nothing
        }

        if (count($this->class_attribute) > 0) {
            $classes = implode(" ", $this->class_attribute);
            $html = $html . ' class="' . $classes . '"';
        } else {
            // no classes specified, do nothing
        }

        $html = $html . " />\n";
        return $html;
    }
}
?>