<?php
require_once 'form_elements.php';
require_once 'inline_elements.php';
require_once 'block_elements.php';

class Yf2eTest2
{
    private static $doctype = "<!DOCTYPE html>";

    private static $indent_unit = "    ";
    private static $indent_level = 0;

    private $html;

    public function __construct($html_title) {
        $this->html = new HtmlElement($html_title);
    }

    public function render() {
        $doc = $this->html->render(self::$indent_unit, self::$indent_level);
        return self::$doctype . "\n" . $doc;
    }
}
?>