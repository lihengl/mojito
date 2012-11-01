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

abstract class HtmlBase implements Renderable
{
    public static $tag_opening = "<";
    public static $tag_closing = ">";

    public static $empty_closing = " />";
    public static $paired_closing = "</";

    public static $attrval_opening = '="';
    public static $attrval_closing = '"';

    public static $id = "id";
    public static $class = "class";

    public static $separator_char = " ";

    protected $tagname;
    protected $attributes;
    protected $children;

    private function render_attributes() {
        $rendering_pieces = array();
        foreach ($this->attributes as $name=>$value) {
            $rendering_piece = "";

            if ($value != "") {
                $rendering_piece = $name . self::$attrval_opening . $value . self::$attrval_closing;
                array_push($rendering_pieces, $rendering_piece);
            } else {
                $rendering_piece = "";
            }
        }
        $attribute_rendering = implode(self::$separator_char, $rendering_pieces);
        return $attribute_rendering;
    }

    private function render_empty($opening, $indent) {
        $rendering = $opening . self::$empty_closing;
        $formatted = $indent . $rendering . "\n";
        return $formatted;
    }

    private function render_paired($opening, $indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);

        $openline = $opening . self::$tag_closing;

        $midlines = "";
        $child_level = $indent_level + 1;

        foreach ($this->children as $child) {
            $midlines .= $child->render($indent_unit, $child_level);
        }

        $closeline = self::$paired_closing . $this->tagname . self::$tag_closing;

        $formatted_open = $indent . $openline . "\n";
        $formatted_mid = $midlines;
        $formatted_close = $indent . $closeline . "\n";

        $formatted = $formatted_open . $formatted_mid . $formatted_close;
        return $formatted;
    }

    public function render($indent_unit, $indent_level) {
        $indent = str_repeat($indent_unit, $indent_level);

        $opening = self::$tag_opening . $this->tagname;
        
        $attribute = $this->render_attributes();

        if ($attribute != "") {
            $opening .= self::$separator_char . $attribute;
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
        $this->attributes[self::$id] = $value;
    }

    public function add_class($value_string) {
        $value = $value_string;
        $existing = $this->attributes[self::$class];

        if (strpos($value, self::$separator_char)) {
            // class value cannot contain space, if so do not add it
            return FALSE;
        } else if ($existing == "") {
            $this->attributes[self::$class] = $value;
            return TRUE;
        } else if (strpos($existing, $value_string)) {
            // class already exists, do nothing
            return FALSE;
        } else {
            $added = $existing . self::$separator_char . $value;
            $this->attributes[self::$class] = $added;
            return TRUE;
        }
    }
}

class HtmlElement extends HtmlBase
{   
    public static $tag = "html";

    public static $charset_value = "UTF-8";

    private $head;
    private $body;

    public function __construct($title_string) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->children = array();        
        $this->head = new HeadElement($this, self::$charset_value, $title_string);
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

class HeadElement extends HtmlBase
{
    public static $tag = "head";

    public function __construct(HtmlBase $parent, $charset, $title) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
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

class BrElement extends HtmlBase
{
    public static $tag = "br";

    public function __construct() {        
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>""); 
        $this->children = NULL;    
    }
}

class BodyElement extends HtmlBase
{
    public static $tag = "body";

    public function __construct(HtmlBase $parent) {
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->children = array();
    }

    public function push(Renderable $element) {
        array_push($this->children, $element);
    }
}

class CharsetMetaElement extends HtmlBase
{
    public static $tag = "meta";

    public static $charset = "charset";

    public function __construct(HeadElement $parent, $charset_value) { 
        $this->tagname = self::$tag;
        
        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$charset] = $charset_value;

        $this->children = NULL;        
    }

    public function set_charset($value) {
        $this->attributes[self::$charset] = $value;
    }
}

class LinkElement extends HtmlBase
{
    public static $tag = "link";

    public static $href = "href";
    public static $type = "type";
    public static $rel = "rel";

    public function __construct($href_value, $type_value, $rel_value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$href] = $href_value;
        $this->attributes[self::$type] = $type_value;
        $this->attributes[self::$rel] = $rel_value;

        $this->children = NULL;
    }

    public function set_hyperlink($reference_url) {
        $this->attriubutes[self::$href] = $reference_url;
    }

    public function set_linktype($value) {
        $this->attributes[self::$type] = $value;
    }

    public function set_relationship($type) {
        $this->attributes[self::$rel] = $type;
    }
}

class TitleElement extends HtmlBase
{
    public static $tag = "title";

    public function __construct(HeadElement $parent, $title_string) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");

        $this->children = array();
        $content_element = new TextElement($title_string);
        array_push($this->children, $content_element);
    }
}

class ScriptElement extends HtmlBase
{
    public static $tag = "script";

    public static $src = "src";

    public function __construct($script_url) { 
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$src] = $script_url;

        $this->children = array();
    }

    public function set_source($script_url) {
        $this->attributes[self::$src] = $script_url;
    }

    public function render($indent_unit, $indent_level) {
        $zeroindented_output = parent::render("    ", 0);
        return $zeroindented_output;
    }
}
?>