<?php
require 'framework/htmls.php';

class MojitoController extends UlElement
{
    private static $id = "controller";

    private static $right_class = "rightsided";
    private static $left_class = "leftsided";    

    private $controls;

    public function __construct() {
        parent::__construct();
        $this->id(self::$id);
    }

    public function right_push(AElement $control) {
        $item = parent::push($control);
        $item->classes(self::$right_class);
    }

    public function left_push(AElement $control) {
        $item = parent::push($control);
        $item->classes(self::$left_class);
    }    
}

class MojitoApplication extends HtmlElement
{
    private static $globalstyle = "mojito/framework/default.css";    
    private static $style = "mojito/config/style.css";

    private static $doctype = "<!DOCTYPE html>\n";
    private static $charset = "UTF-8";

    private static $indent = "    ";

    private $controller;

    public function __construct($title) {
        parent::__construct(self::$charset, $title);

        $this->style_push(self::$globalstyle);
        $this->style_push(self::$style);

        $this->controller = $this->body_push(new MojitoController());        
    }

    public function render() {
        $composed_html = $this->compose(self::$indent, 0);
        echo self::$doctype;
        echo $composed_html;
    }

    public function controller_push($link, $title, $is_rightsided) {
        $control = new AElement($link, $title);

        if ($is_rightsided === TRUE) {
            $this->controller->right_push($control);
        } else {
            $this->controller->left_push($control);
        }

        return $control;
    }    
}
?>