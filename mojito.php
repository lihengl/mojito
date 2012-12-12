<?php
require 'framework/htmls.php';

class SessionBar extends UlElement
{
    private static $id = "sessionbar";

    public function __construct() {
        parent::__construct();
        $this->id(self::$id);

        $this->push_item(new HtmlText("banner"));
        $this->push_item(new HtmlText("username"));
        $this->push_item(new HtmlText("userip"));
    }

}

abstract class MojitoRoom extends HtmlElement
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

        $this->body_push(new SessionBar());       
    }

    public function render() {
        $composed_html = $this->compose(self::$indent, 0);
        echo self::$doctype;
        echo $composed_html;
    }
}
?>