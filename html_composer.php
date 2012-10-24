<?php
require 'html_elements.php';

class HtmlComposer
{
    public static $DOCTYPE = "<!DOCTYPE html>";

    public static $INDENT_UNIT = "    ";
    public static $SEPERATOR_CHAR = " ";

    public static $OPENING_CHAR = "<";
    public static $OPENCLOSE_CHAR = ">";
    public static $EMPTYCLOSE_CHAR = "/>";
    public static $CLOSEOPEN_CHAR = "</";

    private function compose_text(Composable $element, $indent) {
        $html = $element->content;

        return $indent . $html;
    }

    private function compose_openopening(Composable $element) {
        $opening = HtmlComposer::$OPENING_CHAR . $element->name();

        return $opening;
    }

    private function compose_attribute(Composable $element) {
        $attr_names = $element->attributes->names();
        $attr_htmls = array();
        $attr_html = "";

        foreach ($attr_names as $name) {

            if ($name == IdAttribute::$NAME) {
                $attr_value = $element->attributes->get_id();
            } else if ($name == ClassAttribute::$NAME) {
                $class_values = $element->attributes->get_classes();
                $attr_value = implode(HtmlComposer::$SEPERATOR_CHAR,
                                      $class_values);
            } else {
                $attr_value = $element->attributes->get($name);
            }

            $attr_html = $name . '="' . $attr_value . '"';
            array_push($attr_htmls, $attr_html);
        }

        $attribute = implode(" ", $attr_htmls);

        return $attribute;
    }

    private function compose_empty_closing() {
        return HtmlComposer::$EMPTYCLOSE_CHAR;
    }    

    private function compose_paired_openclosing() {
        return HtmlComposer::$OPENCLOSE_CHAR;
    }

    private function format_empty($open, $attr, $close) {
        if ($attr == "") {
            $formatted = $open
                         . HtmlComposer::$SEPERATOR_CHAR
                         . $close;
        } else {
            $formatted = $open
                         . HtmlComposer::$SEPERATOR_CHAR
                         . $attr
                         . HtmlComposer::$SEPERATOR_CHAR
                         . $close;
        }

        return $formatted;
    }

    private function format_paired($open, $attr, $close) {
        if ($attr == "") {
            $formatted = $open . $close;
        } else {
            $formatted = $open
                         . HtmlComposer::$SEPERATOR_CHAR
                         . $attr
                         . $close;
        }

        return $formatted;
    }    

    private function compose_content(Composable $element, $child_level) {
        $children = $element->children->all();
        $content = "";

        foreach ($children as $child) {
            $content .= $this->compose($child, $child_level);
        }

        return $content;
    }

    private function compose_paired_closingtag(Composable $element) {
        $left = HtmlComposer::$CLOSEOPEN_CHAR;
        $right = HtmlComposer::$OPENCLOSE_CHAR;

        return $left . $element->name() . $right;
    }

    public function compose(Composable $element, $indent_level) {
        $indent = str_repeat(HtmlComposer::$INDENT_UNIT, $indent_level);
        $schema = $element->schema();

        if ($schema == Composable::TEXT_ELEMENT_SCHEMA) {
            $composed = $this->compose_text($element, $indent);
        } else if ($schema == Composable::EMPTY_ELEMENT_SCHEMA) {
            $opening = $this->compose_openopening($element);
            $attr = $this->compose_attribute($element);
            $closing = $this->compose_empty_closing();

            $formatted = $this->format_empty($opening, $attr, $closing);

            $composed = $indent . $formatted;
        } else if ($schema == Composable::PAIRED_ELEMENT_SCHEMA) {
            $opening = $this->compose_openopening($element);
            $attr = $this->compose_attribute($element);
            $oclosing = $this->compose_paired_openclosing();

            $opening_tag = $this->format_paired($opening, $attr , $oclosing);

            $paired_begin = $indent . $opening_tag . "\n";            

            $child_level = $indent_level + 1;
            $paired_content = $this->compose_content($element, $child_level);

            $closing_tag = $this->compose_paired_closingtag($element);

            $paired_end = $indent . $closing_tag;
            
            $composed = $paired_begin . $paired_content . $paired_end;
        } else {
            echo "[HtmlComposer] Error: Unknown composite schema";
        }

        $composed .= "\n";

        return $composed;
    }
}
?>