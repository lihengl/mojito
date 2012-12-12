<?php
require 'framework/htmls.php';

class ControlBar extends UlElement
{
    private static $id = "controlbar";

    private $controls;

    public function __construct() {
        parent::__construct();
        $this->id(self::$id);

        $this->push(new HtmlText("隨需平台"), FALSE);
        $this->push(new HtmlText("李易致"), FALSE);
        $this->push(new HtmlText("企展群"), FALSE);        
        $this->push(new HtmlText("登出"), FALSE);
    }

    public function push(HtmlText $control, $is_right) {
        parent::push($control);
    }
}

abstract class MojitoBase extends HtmlElement
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
    }

    public function render() {
        $composed_html = $this->compose(self::$indent, 0);
        echo self::$doctype;
        echo $composed_html;
    }
}

class MojitoPortal extends MojitoBase
{
    public function __construct($title) {
        parent::__construct($title);
    }
}

class MojitoRoom extends MojitoBase
{
    public function __construct($title) {
        parent::__construct($title);
        $this->body_push(new ControlBar());
    }
}

?>