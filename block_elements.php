<?php
require_once 'html_base.php';

interface Sortable
{
    public function sort();
}

class DivElement extends HtmlBase
{
    private static $tag = "div";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = array();
    }
}

class LiElement extends HtmlBase
{
    private static $tag = "li";

    public function __construct(Sortable $host, Renderable $content) {
        parent::__construct(self::$tag);
        $this->children = array($content);
    }

    public function push_text($item_text) {
        $text = new TextElement($item_text);
        array_push($this->children, $text);
        return $text;
    }
}

class OlElement extends HtmlBase implements Sortable
{
    private static $tag = "ol";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = array();
    }

    public function sort() {
        // TODO
    }

    public function push_item(Renderable $content) {
        $item = new LiElement($this, $content);
        array_push($this->children, $item);
        return $item;
    }
}

class PElement extends HtmlBase
{
    private static $tag = "p";

    public function __construct($paragraph_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new TextElement($paragraph_text);     
        array_push($this->children, $text);
    }

    public function push_plain($paragraph_text) {
        $text = new TextElement($paragraph_text);
        array_push($this->children, $text);
    }

    public function push_break() {
        $break = new BrElement();
        array_push($this->children, $break);
    }
}

class HrElement extends HtmlBase
{
    private static $tag = "hr";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = NULL;    
    }
}

class H1Element extends HtmlBase
{
    private static $tag = "h1";

    public function __construct($heading_text) { 
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new TextElement($heading_text);
        array_push($this->children, $text);
    }
}

class H2Element extends HtmlBase
{
    private static $tag = "h2";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new TextElement($heading_text);
        array_push($this->children, $text);
    }
}

class H3Element extends HtmlBase
{
    private static $tag = "h3";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new TextElement($heading_text);
        array_push($this->children, $text);
    }
}

class H4Element extends HtmlBase
{
    private static $tag = "h4";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new TextElement($heading_text);
        array_push($this->children, $text);
    }
}

class H5Element extends HtmlBase
{
    private static $tag = "h5";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new TextElement($heading_text);
        array_push($this->children, $text);
    }
}

class H6Element extends HtmlBase
{
    private static $tag = "h6";

    public function __construct($heading_text) {
        parent::__construct(self::$tag);
        $this->children = array();
           
        $text = new TextElement($heading_text);
        array_push($this->children, $text);
    }
}
?>