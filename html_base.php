<?php
interface Renderable
{
    public function name();
    public function render($indent_unit, $indent_level);
}

class TextElement implements Renderable
{
    public $content;

    public function __construct($text_content) {
        $this->content = $text_content;
    }

    public function name() {
        return "#";
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
    private static $tag_opening = "<";
    private static $tag_closing = ">";

    private static $empty_closing = " />";
    private static $paired_closing = "</";

    private static $attrval_opening = '="';
    private static $attrval_closing = '"';

    private static $id = "id";
    private static $class = "class";

    private static $separator_char = " ";

    protected $tagname;
    protected $attributes;
    protected $children;

    public function __construct($tagname) {
        $this->tagname = $tagname;
        $this->attributes = array(self::$id=>"", self::$class=>"");
    }

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

    public function name() {
        return $this->tagname;
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

    public function classes($value = NULL) {
        $existing = $this->attributes[self::$class];

        if ($value === NULL) {
            $values = explode(self::$separator_char, $existing);
            return $values;
        } else if (strpos($value, self::$separator_char)) {
            return "";
        } else if ($existing == "") {
            $this->attributes[self::$class] = $value;
            return $value;
        } else if (strpos($existing, $value)) {
            return "";
        } else {
            $adding = $existing . self::$separator_char . $value;
            $this->attributes[self::$class] = $adding;
            return $value;
        }
    }

    public function attribute($name, $value = NULL) {
        if ($value === NULL) {
            return $this->attributes[$name];
        } else {
            $this->attributes[$name] = $value;
            return $value;
        }
    }

    public function id($value = NULL) {
        return $this->attribute(self::$id, $value);
    }
}

class HtmlElement extends HtmlBase
{   
    private static $tag = "html";

    private static $charset_value = "UTF-8";

    private static $csstype_value = "text/css";
    private static $cssrel_value = "stylesheet";

    private $head;
    private $body;

    public function __construct($title_string) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->head = new HeadElement($this, self::$charset_value, $title_string);
        array_push($this->children, $this->head);

        $this->body = new BodyElement($this);
        array_push($this->children, $this->body);
    }

    // assuming there is only link for css
    public function attach_style($stylesheet_url) {
        $stylelink = new LinkElement($this->head);
        $stylelink->href($stylesheet_url);
        $stylelink->type(self::$csstype_value);
        $stylelink->rel(self::$cssrel_value);

        $this->head->push_link($stylelink);
        
        return $stylelink;
    }

    public function body_push(Renderable $element) {
        $this->body->push($element);
    }
}

class HeadElement extends HtmlBase
{
    private static $tag = "head";

    public function __construct(HtmlBase $host, $charset_value, $title) {
        parent::__construct(self::$tag);
        $this->children = array();

        $meta = new MetaElement($this);
        $meta->charset($charset_value);
        array_push($this->children, $meta);

        $title = new TitleElement($this, $title);
        array_push($this->children, $title);
    }

    public function push_link(LinkElement $link) {
        array_push($this->children, $link);
        return $link;
    }
}

class BrElement extends HtmlBase
{
    private static $tag = "br";

    public function __construct() {
        parent::__construct(self::$tag);
        $this->children = NULL;    
    }
}

class BodyElement extends HtmlBase
{
    private static $tag = "body";

    public function __construct(HtmlBase $host) {
        parent::__construct(self::$tag);
        $this->children = array();
    }

    public function push(HtmlBase $element) {
        array_push($this->children, $element);
    }
}

class MetaElement extends HtmlBase
{
    private static $tag = "meta";

    private static $charset = "charset";

    public function __construct(HeadElement $host) {
        parent::__construct(self::$tag);
        $this->children = NULL;        

        $this->attributes[self::$charset] = "";
    }

    public function charset($value) {
        return $this->attribute(self::$charset, $value);
    }
}

class LinkElement extends HtmlBase
{
    private static $tag = "link";

    private static $href = "href";
    private static $type = "type";
    private static $rel = "rel";

    public function __construct(HeadElement $host) {
        parent::__construct(self::$tag);
        $this->children = NULL;
    }

    public function href($value) {
        return $this->attribute(self::$href, $value);
    }

    public function type($value) {
        return $this->attribute(self::$type, $value);
    }

    public function rel($value) {
        return $this->attribute(self::$rel, $value);
    }
}

class TitleElement extends HtmlBase
{
    private static $tag = "title";

    public function __construct(HeadElement $parent, $title_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new TextElement($title_text);
        array_push($this->children, $text);
    }
}

class ScriptElement extends HtmlBase
{
    private static $tag = "script";

    private static $src = "src";

    public function __construct($script_url) { 
        parent::__construct(self::$tag);
        $this->children = array();        

        $this->attributes[self::$src] = $script_url;
    }

    public function src($script_url) {
        return $this->attribute(self::$src, $script_url);
    }

    public function render($indent_unit, $indent_level) {
        $zeroindented_output = parent::render("    ", 0);
        return $zeroindented_output;
    }
}
?>