<?php
require_once 'html_base.php';

class DivElement extends HtmlBase
{
    public static $tag = "div";

    public function __construct() {      
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->children = array();
    }
}

class PElement extends HtmlBase
{
    public static $tag = "p";

    public function __construct($content_string) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->children = array();
        $text = new TextElement($content_string);     
        array_push($this->children, $text);
    }

    public function push_text($content_string) {
        $text = new TextElement($content_string);
        array_push($this->children, $text);
    }

    public function push_break() {
        $break = new BrElement();
        array_push($this->children, $break);
    }
}

class HrElement extends HtmlBase
{
    public static $tag = "hr";

    public function __construct() {        
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>""); 
        $this->children = NULL;    
    }
}

class H1Element extends HtmlBase
{
    public static $tag = "h1";

    public function __construct($content_string) { 
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H2Element extends HtmlBase
{
    public static $tag = "h2";

    public function __construct($content_string) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->children = array();        
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H3Element extends HtmlBase
{
    public static $tag = "h3";

    public function __construct($content_string) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");

        $this->children = array();           
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H4Element extends HtmlBase
{
    public static $tag = "h4";

    public function __construct($content_string) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");

        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H5Element extends HtmlBase
{
    public static $tag = "h5";

    public function __construct($content_string) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");

        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H6Element extends HtmlBase
{
    public static $tag = "h6";

    public function __construct($content_string) { 
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}
?>