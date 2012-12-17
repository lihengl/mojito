<?php
require 'framework/htmls.php';

interface Configurable
{
    public function configure($file);
}

class MojitoNavbar extends UlElement implements Configurable
{
    private static $id = "navbar";
    private static $id_hidden = "navbar-hidden";

    private static $class_left = "left-sided";
    private static $class_right = "right-sided";

    private $controls;

    private function item_push($title, $link) {
        $item = new AElement($link, $title);
        $item = $this->push($item);
        return $item;
    }

    public function __construct() {
        parent::__construct();
        $this->id(self::$id);
    }

    public function configure($file) {
        $key_leftitems = "leftitems";
        $key_rightitems = "rightitems";
        $key_title = "title";
        $key_link = "link";

        $content = file_get_contents($file);
        $config = json_decode($content, TRUE);

        $left_items = $config[$key_leftitems];

        foreach($left_items as $item) {
            $pushed = $this->item_push($item[$key_title], $item[$key_link]);
            $pushed->classes(self::$class_left);
        }

        $right_items = $config[$key_rightitems];

        foreach($right_items as $item) {
            $pushed = $this->item_push($item[$key_title], $item[$key_link]);
            $pushed->classes(self::$class_right);
        }
    }

    public function hide() {
        $this->id(self::$id_hidden);
    }

    public function show() {
        $this->id(self::$id);
    }
}

class MojitoDocument extends HtmlElement
{
    private static $doctype = "<!DOCTYPE html>\n";
    private static $charset = "UTF-8";
    private static $indent = "    ";

    public function __construct($title) {
        parent::__construct(self::$charset, $title);
    }

    public function output() {
        $document = $this->compose(self::$indent, 0);
        return self::$doctype . $document;
    }
}

class MojitoApplication
{
    private static $stylesheet_global = "mojito/mojito.css";    
    private static $stylesheet_custom = "mojito/config/custom.css";
    private static $configfile_navbar = "mojito/config/navbar.json";

    private $navbar;

    protected $dom;    

    public function __construct($title) {
        $this->dom = new MojitoDocument($title);
        $this->dom->style_push(self::$stylesheet_global);
        $this->dom->style_push(self::$stylesheet_custom);

        $this->navbar = $this->dom->body_push(new MojitoNavbar());
        $this->navbar->configure(self::$configfile_navbar);
    }

    public function respond() {
        $html = $this->dom->output();
        echo $html;
    }
}
?>