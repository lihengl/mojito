<?php
require_once 'html_base.php';

class SpanElement extends HtmlBase
{
    private static $tag = "span";

    public function __construct() { 
        parent::__construct(self::$tag);
        $this->children = array();
    }
}

class AElement extends HtmlBase
{
    private static $tag = "a";

    private static $href = "href";
    private static $target = "target";

    private static $blankwin_value = "_blank";

    public function __construct($link_url, $link_text) { 
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$href] = $link_url;
        $this->attributes[self::$target] = "";

        $text = new TextElement($link_text);
        array_push($this->children, $text);
    }

    public function href($link_url) {
        return $this->attribute(self::$href, $link_url);
    }

    public function blankwindow($use) {
        if ($use === TRUE) {
            $this->attribute[self::$target] = self::$blankwin_value;
        } else {
            $this->attribute[self::$target] = "";
        }
    }
}

class ButtonElement extends HtmlBase
{
    private static $tag = "button";

    public function __construct($button_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new TextElement($button_text);
        array_push($this->children, $text);
    }
}

class ImgElement extends HtmlBase
{
    private static $tag = "img";

    private static $src = "src";
    private static $alt = "alt";
    private static $title = "title";

    public function __construct($src_value, $alt_value) {
        parent::__construct(self::$tag);
        $this->children = NULL;
        
        $this->attributes[self::$src] = $src_value;
        $this->attributes[self::$alt] = $alt_value;
        $this->attributes[self::$title] = "";
    }

    public function src($image_url = NULL) {
        return $this->attribute(self::$src, $image_url);
    }

    public function alt($text = NULL) {
        return $this->attribute(self::$alt, $text);
    }

    public function title($text = NULL) {
        return $this->attribute(self::$title, $text);
    }
}
?>