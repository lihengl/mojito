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

    private function render_text(Renderable $element) {
        $html = $element->content;
        return $html;
    }

    private function render_tagopening($element_name) {
        $html = HtmlRenderer::$OPENING_CHAR . $element_name;
        return $html;
    }

    private function render_attribute(HtmlAttributes $attribute) {
        $attribute_htmls = array();

        foreach ($attribute->names() as $name) {

            if ($name == IdAttribute::$NAME) {
                $value = $attribute->get_id();
            } else if ($name == ClassAttribute::$NAME) {
                $classes = $attribute->get_classes();
                $value = implode(HtmlRenderer::$SEPARATOR, $classes);
            } else {
                $value = $attribute->get($name);
            }

            $attribute_html = $name . '="' . $value . '"';
            array_push($attribute_htmls, $attribute_html);
        }

        $html = implode(HtmlRenderer::$SEPARATOR, $attribute_htmls);

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
        $attribute = $this->render_attribute($element->attributes);
        $closing = HtmlRenderer::$EMPTY_CLOSING;

        if ($attribute == "") {
            $html = $opening
                  . HtmlRenderer::$SEPARATOR
                  . HtmlRenderer::$EMPTY_CLOSING;
        } else {
            $html = $opening
                  . HtmlRenderer::$SEPARATOR
                  . $attribute
                  . HtmlRenderer::$SEPARATOR
                  . HtmlRenderer::$EMPTY_CLOSING;
        }

        return $html;
    }

    private function render_paired(Renderable $element, $indent_level) {
        $name = $element->name();
        $indent = str_repeat(HtmlRenderer::$INDENT_UNIT, $indent_level);

        $opentag_begin = $this->render_tagopening($name);
        $attribute = $this->render_attribute($element->attributes);
        $opentag_end = HtmlRenderer::$CLOSING_CHAR;

        $opentag = "";

        if ($attribute == "") {
            $opentag = $opentag_begin . $opentag_end;
        } else {            
            $opentag = $opentag_begin
                     . HtmlRenderer::$SEPARATOR
                     . $attribute
                     . $opentag_end;
        }

        $level = $indent_level + 1;
        $content = $this->render_content($element->children, $level);

        $closetag = HtmlRenderer::$PAIRED_CLOSING
                  . $name
                  . HtmlRenderer::$CLOSING_CHAR;

        $html = $indent . $opentag . "\n"
              . $content
              . $indent . $closetag;

        return $html;
    }

    public function render(Renderable $element, $indent_level) {
        $indent = str_repeat(HtmlRenderer::$INDENT_UNIT, $indent_level);
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
}
?>