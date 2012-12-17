<?php
require 'framework/htmls.php';

class MojitoNavbar extends UlElement
{
    private static $id = "navbar";

    private static $right_class = "right-sided";
    private static $left_class = "left-sided";    

    private $controls;

    private function item_push($title, $link) {
        $item = new AElement($link, $title);
        $item = parent::push($item);
        return $item;
    }

    public function __construct() {
        parent::__construct();
        $this->id(self::$id);
    }

    public function right_push($title, $link) {
        $item = $this->item_push($title, $link);
        $item->classes(self::$right_class);
    }

    public function left_push($title, $link) {
        $item = $this->item_push($title, $link);
        $item->classes(self::$left_class);
    }    
}

class MojitoApplication extends HtmlElement
{
    private static $globalstyle = "mojito/mojito.css";    
    private static $customstyle = "mojito/config/custom.css";

    private static $doctype = "<!DOCTYPE html>\n";
    private static $charset = "UTF-8";

    private static $indent = "    ";

    public $controller;

    public function __construct($title) {
        parent::__construct(self::$charset, $title);

        $this->style_push(self::$globalstyle);
        $this->style_push(self::$customstyle);

        $this->controller = $this->body_push(new MojitoNavbar());        
    }

    public function respond() {
        echo self::$doctype;

        $composed_html = $this->compose(self::$indent, 0);
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