<?php
require 'framework/htmls.php';

class SessionBar extends UlElement
{
    public function __construct($username) {
        parent::__construct();
        $this->id("topbar");
    }
}

class MojitoDocument extends HtmlElement
{
    private static $style = "mojito/config/style.css";
    private static $stylebase = "mojito/framework/cssbase.css";

    private static $doctype = "<!DOCTYPE html>\n";
    private static $charset = "UTF-8";
    private static $indent = "    ";

    public function __construct($title) {
        parent::__construct(self::$charset, $title);

        $this->style_push(self::$stylebase);
        $this->style_push(self::$style);

        $this->body_push(new SessionBar("Li-Heng Liang"));

        $heading = $this->body_push(new H1Element("隨需平台"));
        $heading->classes("small-sized");
        $heading->classes("default-tinted");
    }

    public function render() {
        $composed_html = $this->compose(self::$indent, 0);
        echo self::$doctype;
        echo $composed_html;
    }
}
?>