<?php
require 'framework/htmls.php';

interface RequestHandler
{
    public function get($parameters);
    public function post($parameters);
}

class NavbarElement extends UlElement
{
    private static $configfile = "mojito/config/navbar.json";

    private static $id = "navbar";

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

    public function configure() {
        $key_leftitems = "leftitems";
        $key_rightitems = "rightitems";
        $key_title = "title";
        $key_link = "link";

        $content = file_get_contents(self::$configfile);
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
}

class CoverElement extends DivElement
{
    private static $id = "cover";

    public function __construct() {
        parent::__construct();
        $this->id(self::$id);

     //   $this->push(new H1Element("隨需平台"));
    }
}

class MojitoDocument extends HtmlElement
{
    private static $doctype = "<!DOCTYPE html>\n";
    private static $charset = "UTF-8";
    private static $indent = "    ";

    private static $stylesheet_global = "mojito/mojito.css";
    private static $stylesheet_custom = "mojito/config/custom.css";

    private static $class_hidden = "navbar-hidden";    

    private $navbar;
    private $header;
    private $footer;

    public $bodylist;

    public function __construct($title) {
        parent::__construct(self::$charset, $title);

        $this->style_push(self::$stylesheet_global);
        $this->style_push(self::$stylesheet_custom);

        $this->navbar = new NavbarElement();
        $this->navbar->configure();
        $this->body_push($this->navbar);

        $this->cover = new CoverElement();
        //$this->body_push($this->cover);        
    }

    public function render() {
        $document = $this->compose(self::$indent, 0);
        return self::$doctype . $document;
    }

    public function hide($hide = NULL) {
        if ($hide === TRUE) {          
            $this->navbar->id(self::$class_hidden);
        } else {
        }
    }
}

class MojitoDatabase
{

}

class MojitoApplication implements RequestHandler
{
    protected $database;    
    protected $document;

    public function __construct($title) {
        $this->document = new MojitoDocument($title);     
    }

    public function get($parameters) {
        $this->document->hide(TRUE);        
        return $this->document->render();
    }

    public function post($parameters) {

        $this->document->body_push(new PElement("hihi"));
        $this->document->body_push(new BrElement());

        $pdo = new PDO('mysql:host=127.0.0.1;dbname=firstins', 'fws', 'fws', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $statement = $pdo->query("SELECT * FROM user");
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        foreach($row as $entry) {
            $this->document->body_push(new H1Element($entry));
        }

        return $this->document->render();
    }
}
?>