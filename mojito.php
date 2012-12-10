<?php
require 'modules/html_elements.php';

class MojitoTopbar extends UlElement
{
    public function __construct($username) {
        parent::__construct();
        $this->id("topbar");
    }
}

class MojitoDocument
{
    private static $style = "mojito/config/style.css";
    private static $stylebase = "mojito/modules/style_base.css";

    private static $charset = "UTF-8";
    private static $indent = "    ";

    private $dom;

    public function __construct($title) {
        $this->dom = new HtmlElement(self::$charset, $title);
        $this->dom->style_push(self::$stylebase);
        $this->dom->style_push(self::$style);
        $this->dom->body_push(new MojitoTopbar("Li-Heng Liang"));

        $heading = $this->dom->body_push(new H1Element("FIRSURANCE"));
        $heading->classes("center-aligned");
        $heading->classes("small-sized");
        $heading->classes("default-tinted");
    }

    public function render() {
        $composed_html = $this->dom->compose(self::$indent, 0);
        echo $composed_html;
    }
}
?>