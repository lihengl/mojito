<?php
require 'html_elements.php';

class HtmlComposer
{
    public static $DOCTYPE_MARKUP = "<!DOCTYPE html>";

    public static $INDENT_UNIT = "    ";
    public static $SEPARATOR = " ";

    public static $OPENING_CHAR = "<";
    public static $CLOSING_CHAR = ">";
    public static $EMPTY_CLOSING = "/>";
    public static $PAIRED_CLOSING = "</";

    private function compose_text(Composable $element) {
        $html = $element->content;
        return $html;
    }

    private function compose_tagopening($element_name) {
        $html = HtmlComposer::$OPENING_CHAR . $element_name;
        return $html;
    }

    private function compose_attribute(HtmlAttributes $attribute) {
        $attribute_htmls = array();

        foreach ($attribute->names() as $name) {

            if ($name == IdAttribute::$NAME) {
                $value = $attribute->get_id();
            } else if ($name == ClassAttribute::$NAME) {
                $classes = $attribute->get_classes();
                $value = implode(HtmlComposer::$SEPARATOR, $classes);
            } else {
                $value = $attribute->get($name);
            }

            $attribute_html = $name . '="' . $value . '"';
            array_push($attribute_htmls, $attribute_html);
        }

        $html = implode(HtmlComposer::$SEPARATOR, $attribute_htmls);

        return $html;
    }

    private function compose_content(HtmlChildren $children, $indent_level) {
        $child_elements = $children->all();
        $html = "";

        foreach ($child_elements as $element) {
            // a recursion here
            $html .= $this->compose($element, $indent_level);
        }

        return $html;
    }

    private function compose_empty(Composable $element) {
        $opening = $this->compose_tagopening($element->name());
        $attribute = $this->compose_attribute($element->attributes);
        $closing = HtmlComposer::$EMPTY_CLOSING;

        if ($attribute == "") {
            $html = $opening
                  . HtmlComposer::$SEPARATOR
                  . HtmlComposer::$EMPTY_CLOSING;
        } else {
            $html = $opening
                  . HtmlComposer::$SEPARATOR
                  . $attribute
                  . HtmlComposer::$SEPARATOR
                  . HtmlComposer::$EMPTY_CLOSING;
        }

        return $html;
    }

    private function compose_paired(Composable $element, $indent_level) {
        $name = $element->name();
        $indent = str_repeat(HtmlComposer::$INDENT_UNIT, $indent_level);

        $opentag_begin = $this->compose_tagopening($name);
        $attribute = $this->compose_attribute($element->attributes);
        $opentag_end = HtmlComposer::$CLOSING_CHAR;

        $opentag = "";

        if ($attribute = "") {
            $opentag = $opentag_begin . $opentag_end;
        } else {
            $opentag = $opentag_begin
                     . HtmlComposer::$SEPARATOR
                     . $attribute
                     . $opentag_end;
        }

        $level = $indent_level + 1;
        $content = $this->compose_content($element->children, $level);

        $closetag = HtmlComposer::$PAIRED_CLOSING
                  . $name
                  . HtmlComposer::$CLOSING_CHAR;

        $html = $indent . $opentag . "\n"
              . $content
              . $indent . $closetag;

        return $html;
    }

    public function compose(Composable $element, $indent_level) {
        $indent = str_repeat(HtmlComposer::$INDENT_UNIT, $indent_level);
        $schema = $element->schema();

        $html = "";

        if ($schema == Composable::EMPTY_ELEMENT_SCHEMA) {
            $composed = $this->compose_empty($element);
            $html = $indent . $composed;
        } else if ($schema == Composable::PAIRED_ELEMENT_SCHEMA) {
            $html = $this->compose_paired($element, $indent_level);
        } else if ($schema == Composable::TEXT_ELEMENT_SCHEMA) {
            $html = $indent . $this->compose_text($element);            
        } else {
            echo "[HtmlComposer] Error: Unknown composite schema";
        }

        $html .= "\n";

        return $html;
    }
}
?>