<?php
require_once 'markup_composer.php';

class MarkupChildren
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
?>