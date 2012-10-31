<?php
require_once 'html_base.php';

class DivElement extends HtmlElement
{
    public static $TAGNAME = "div";

    public function __construct() {      
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();
    }
}

class PElement extends HtmlElement
{
    public static $TAGNAME = "p";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
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

class BrElement extends HtmlElement
{
    public static $TAGNAME = "br";

    public function __construct() {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = NULL;
    }
}

class HrElement extends HtmlElement
{
    public static $TAGNAME = "hr";

    public function __construct() {        
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>""); 
        $this->children = NULL;    
    }
}

class H1Element extends HtmlElement
{
    public static $TAGNAME = "h1";

    public function __construct($content_string) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H2Element extends HtmlElement
{
    public static $TAGNAME = "h2";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();        
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H3Element extends HtmlElement
{
    public static $TAGNAME = "h3";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();           
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H4Element extends HtmlElement
{
    public static $TAGNAME = "h4";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H5Element extends HtmlElement
{
    public static $TAGNAME = "h5";

    public function __construct($content_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}

class H6Element extends HtmlElement
{
    public static $TAGNAME = "h6";

    public function __construct($content_string) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $content = new TextElement($content_string);
        array_push($this->children, $content);
    }
}
?>