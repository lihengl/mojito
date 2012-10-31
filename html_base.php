<?php
interface Renderable
{
    public function render($indent_unit, $indent_level);
}

class TextElement implements Renderable
{
    public $content;

    public function __construct($text_content) {
        $this->content = $text_content;
    }

    public function render($indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);
        $output = "";
        $text_content = $this->content;

        if ($text_content == "") {
            $output = "";
        } else {
            $output = $indent . $text_content . "\n";
        }
        
        return $output;
    }
}

abstract class HtmlElement implements Renderable
{
    public static $OPENING = "<";
    public static $CLOSING = ">";

    public static $EMPTYCLOSING = " />";
    public static $PAIREDCLOSING = "</";

    public static $ATTROPEN = '="';
    public static $ATTRCLOSE = '"';

    public static $IDATTR = "id";
    public static $CLASSATTR = "class";

    public static $SEPARATOR = " ";

    protected $name;
    protected $attributes;
    protected $children;

    private function render_attributes() {
        $rendering_pieces = array();
        foreach ($this->attributes as $name=>$value) {
            $rendering_piece = "";

            if ($value != "") {
                $rendering_piece = $name . self::$ATTROPEN . $value . self::$ATTRCLOSE;
                array_push($rendering_pieces, $rendering_piece);
            } else {
                $rendering_piece = "";
            }
        }
        $attribute_rendering = implode(self::$SEPARATOR, $rendering_pieces);
        return $attribute_rendering;
    }

    private function render_empty($opening, $indent) {
        $rendering = $opening . self::$EMPTYCLOSING;
        $formatted = $indent . $rendering . "\n";
        return $formatted;
    }

    private function render_paired($opening, $indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);

        $openline = $opening . self::$CLOSING;

        $midlines = "";
        $child_level = $indent_level + 1;

        foreach ($this->children as $child) {
            $midlines .= $child->render($indent_unit, $child_level);
        }

        $closeline = self::$PAIREDCLOSING . $this->name . self::$CLOSING;

        $formatted_open = $indent . $openline . "\n";
        $formatted_mid = $midlines;
        $formatted_close = $indent . $closeline . "\n";

        $formatted = $formatted_open . $formatted_mid . $formatted_close;
        return $formatted;
    }

    public function render($indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);
        $name = $this->name;

        $opening = self::$OPENING . $name;
        $attribute = $this->render_attributes();

        if ($attribute != "") {
            $opening .= self::$SEPARATOR . $attribute;
        } else {
            // attribute is empty and has nothing te be put into opening
            $opening = $opening; 
        }

        $output = "";

        if ($this->children === NULL) {
            $output = $this->render_empty($opening, $indent);
        } else {
            // shorten variable names to follow the 80-column rule
            $unit = $indent_unit;
            $level = $indent_level;
            $output = $this->render_paired($opening, $unit, $level);
        }

        return $output;
    }

    public function set_id($value_string) {
        $value = $value_string;
        $this->attributes[self::$IDATTR] = $value;
    }

    public function add_class($value_string) {
        $value = $value_string;
        $existing = $this->attributes[self::$CLASSATTR];

        if (strpos($value, self::$SEPARATOR)) {
            // class value cannot contain space, if so do not add it
            return FALSE;
        } else if ($existing == "") {
            $this->attributes[self::$CLASSATTR] = $value;
            return TRUE;
        } else if (strpos($existing, $value_string)) {
            // class already exists, do nothing
            return FALSE;
        } else {
            $added = $existing . self::$SEPARATOR . $value;
            $this->attributes[self::$CLASSATTR] = $added;
            return TRUE;
        }
    }
}

class HtmlRootElement extends HtmlElement
{   
    public static $TAGNAME = "html";

    public static $CHARSET = "UTF-8";

    private $head;
    private $body;

    public function __construct($title_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();        
        $this->head = new HeadElement($this, self::$CHARSET, $title_string);
        $this->body = new BodyElement($this);
        array_push($this->children, $this->head);
        array_push($this->children, $this->body);
    }

    public function head_push(Renderable $element) {
        $this->head->push($element);
    }

    public function body_push(Renderable $element) {
        $this->body->push($element);
    }
}

class HeadElement extends HtmlElement
{
    public static $TAGNAME = "head";

    public function __construct(HtmlElement $parent, $charset, $title) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $charset = new CharsetMetaElement($this, $charset);
        $title = new TitleElement($this, $title);
        array_push($this->children, $charset);
        array_push($this->children, $title);
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
    }
}

class BodyElement extends HtmlElement
{
    public static $TAGNAME = "body";

    public function __construct(HtmlElement $parent) {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
    }
}

class CharsetMetaElement extends HtmlElement
{
    public static $TAGNAME = "meta";

    public static $CHARSETATTR = "charset";

    public function __construct(HeadElement $parent, $charset_value) { 
        $this->name = self::$TAGNAME;
        
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$CHARSETATTR] = $charset_value;

        $this->children = NULL;        
    }

    public function set_charset($value) {
        $this->attributes[self::$CHARSETATTR] = $$value;
    }
}

class LinkElement extends HtmlElement
{
    public static $TAGNAME = "link";

    public static $HREFATTR = "href";
    public static $TYPEATTR = "type";
    public static $RELATTR = "rel";

    public function __construct($href_value, $type_value, $rel_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$HREFATTR] = $href_value;
        $this->attributes[self::$TYPEATTR] = $type_value;
        $this->attributes[self::$RELATTR] = $rel_value;

        $this->children = NULL;
    }

    public function set_hyperlink($reference_url) {
        $this->attriubutes[self::$HREFATTR] = $reference_url;
    }

    public function set_linktype($value) {
        $this->attributes[self::$TYPEATTR] = $value;
    }

    public function set_relationship($type) {
        $this->attributes[self::$RELATTR] = $type;
    }
}

class TitleElement extends HtmlElement
{
    public static $TAGNAME = "title";

    public function __construct(HeadElement $parent, $title_string) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $content_element = new TextElement($title_string);
        array_push($this->children, $content_element);
    }
}

class ScriptElement extends HtmlElement
{
    public static $TAGNAME = "script";

    public static $SRCATTR = "src";

    public function __construct($script_url) { 
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$SRCATTR] = $script_url;

        $this->children = array();
    }

    public function set_source($script_url) {
        $this->attributes[self::$SRCATTR] = $script_url;
    }

    public function render($indent_unit, $indent_level) {
        $zeroindented_output = parent::render("    ", 0);
        return $zeroindented_output;
    }
}
?>