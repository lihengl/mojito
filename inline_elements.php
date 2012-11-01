<?php
require_once 'html_base.php';

class SpanElement extends HtmlBase
{
    public static $tag = "span";

    public function __construct() { 
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->children = array();
    }
}

class AElement extends HtmlBase
{
    public static $tag = "a";

    public static $href = "href";
    public static $target = "target";

    public static $blankwin_value = "_blank";

    public function __construct($href_url, $link_text) { 
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$href] = $href_url;
        $this->attributes[self::$target] = "";

        $this->children = array();
        $content_element = new TextElement($link_text);
        array_push($this->children, $content_element);
    }

    public function set_hyperlink($reference_url) {
        $this->attributes[self::$href] = $reference_url;
    }

    public function use_blankwindow() {
        $this->attributes[self::$target] = self::$blankwin_value;
    }
}

class ImgElement extends HtmlBase
{
    public static $tag = "img";

    public static $src = "src";
    public static $alt = "alt";
    public static $title = "title";

    public function __construct($src_value, $alt_value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$src] = $src_value;
        $this->attributes[self::$alt] = $alt_value;

        $this->children = NULL;
    }

    public function set_source($image_url) {
        $this->attriubutes[self::$src] = $image_url;
    }

    public function set_alternative($info_text) {
        $encoded_text = $info_text;
        $this->attributes[self::$alt] = $encoded_text;
    }

    public function set_title($text) {
        $encoded_text = $text;
        $this->attriubutes[self::$title] = $encoded_text;
    }
}
?>