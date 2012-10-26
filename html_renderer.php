<?php
require 'html_elements.php';

class HtmlRenderer
{
    public static $DOCTYPE_MARKUP = "<!DOCTYPE html>";

    public static $INDENT_UNIT = "    ";
    public static $SEPARATOR = " ";

    public static $OPENING_CHAR = "<";
    public static $CLOSING_CHAR = ">";
    public static $EMPTY_CLOSING = "/>";
    public static $PAIRED_CLOSING = "</";

    public static $ATTRVALUE_BEFORE = '="';
    public static $ATTRVALUE_AFTER = '"';

    private static $INSTANCE = null;

    // only have one static instance of HtmlRenderer at all time
    private function __construct() {

    }

    private function render_text(Renderable $element) {
        $html = $element->content;
        return $html;
    }

    private function render_tagopening($element_name) {
        $html = self::$OPENING_CHAR . $element_name;
        return $html;
    }

    private function render_attribute($attributes) {
        $attribute_htmls = array();

        foreach ($attributes as $name=>$value) {
            $attribute_html = $name
                            . self::$ATTRVALUE_BEFORE
                            . $value
                            . self::$ATTRVALUE_AFTER;
            array_push($attribute_htmls, $attribute_html);
        }

        $html = implode(self::$SEPARATOR, $attribute_htmls);

        return $html;
    }

    private function render_content(HtmlChildren $children, $indent_level) {
        $child_elements = $children->all();
        $html = "";

        foreach ($child_elements as $element) {
            // a recursion here
            $html .= $this->render($element, $indent_level);
        }

        return $html;
    }

    private function render_empty(Renderable $element) {
        $opening = $this->render_tagopening($element->name());
        $attribute = $this->render_attribute($element->attributes());
        $closing = self::$EMPTY_CLOSING;

        if ($attribute == "") {
            $html = $opening
                  . self::$SEPARATOR
                  . self::$EMPTY_CLOSING;
        } else {
            $html = $opening
                  . self::$SEPARATOR
                  . $attribute
                  . self::$SEPARATOR
                  . self::$EMPTY_CLOSING;
        }

        return $html;
    }

    private function render_paired(Renderable $element, $indent_level) {
        $name = $element->name();
        $indent = str_repeat(self::$INDENT_UNIT, $indent_level);

        $opentag_begin = $this->render_tagopening($name);
        $attribute = $this->render_attribute($element->attributes());
        $opentag_end = self::$CLOSING_CHAR;

        $opentag = "";

        if ($attribute == "") {
            $opentag = $opentag_begin . $opentag_end;
        } else {            
            $opentag = $opentag_begin
                     . self::$SEPARATOR
                     . $attribute
                     . $opentag_end;
        }

        $level = $indent_level + 1;
        $content = $this->render_content($element->children, $level);

        $closetag = self::$PAIRED_CLOSING
                  . $name
                  . self::$CLOSING_CHAR;

        $html = $indent . $opentag . "\n"
              . $content
              . $indent . $closetag;

        return $html;
    }

    public function render(Renderable $element, $indent_level) {
        $indent = str_repeat(self::$INDENT_UNIT, $indent_level);
        $schema = $element->schema();

        $html = "";

        if ($schema == Renderable::SINGLE) {
            $renderd = $this->render_empty($element);
            $html = $indent . $renderd;
        } else if ($schema == Renderable::PAIRED) {
            $html = $this->render_paired($element, $indent_level);
        } else if ($schema == Renderable::TEXT) {
            $html = $indent . $this->render_text($element);            
        } else {
            echo "[HtmlRenderer] Error: Unknown composite schema";
        }

        $html .= "\n";

        return $html;
    }

    public static function instance() {

        if (self::$INSTANCE == null) {
            self::$INSTANCE = new HtmlRenderer();
        } else {
            // already instantiated
        }

        return self::$INSTANCE;
    }    
}
?>