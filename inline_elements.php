<?php
require_once 'html_bases.php';

class SpanElement extends HtmlElement
{
    public static $TAGNAME = "span";

    public function __construct() { 
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();
    }
}

class AElement extends HtmlElement
{
    public static $TAGNAME = "a";

    public static $HREFATTR = "href";
    public static $TARGETATTR = "target";

    public static $BLNKWINDOW = "_blank";

    public function __construct($href_url, $link_text) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$HREFATTR] = $href_url;
        $this->attributes[self::$TARGETATTR] = "";

        $this->children = array();
        $content_element = new TextElement($link_text);
        array_push($this->children, $content_element);
    }

    public function set_hyperlink($reference_url) {
        $this->attributes[self::$HREFATTR] = $reference_url;
    }

    public function use_blankwindow() {
        $this->attributes[self::$TARGETATTR] = self::$BLNKWINDOW;
    }
}

class ImgElement extends HtmlElement
{
    public static $TAGNAME = "img";

    public static $SRCATTR = "src";
    public static $ALTATTR = "alt";
    public static $TITLEATTR = "title";

    public function __construct($src_value, $alt_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$SRCATTR] = $src_value;
        $this->attributes[self::$ALTATTR] = $alt_value;

        $this->children = NULL;
    }

    public function set_source($image_url) {
        $this->attriubutes[self::$SRCATTR] = $image_url;
    }

    public function set_alternative($info_text) {
        $encoded_text = $info_text;
        $this->attributes[self::$ALTATTR] = $encoded_text;
    }

    public function set_title($text) {
        $encoded_text = $text;
        $this->attriubutes[self::$TITLEATTR] = $encoded_text;
    }
}
?>