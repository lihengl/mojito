<?php
interface Composable
{
    const TEXT_ELEMENT_SCHEMA = "schema for text element";
    const EMPTY_ELEMENT_SCHEMA = "schema for empty markup element";
    const PAIRED_ELEMENT_SCHEMA = "schema of paired markup element";

    public function name();
    public function schema();
}

class MarkupComposer
{
    public static $HTML5_DOCTYPE = "<!DOCTYPE html>";

    public static $INDENT_UNIT = "    ";

    public static $OPENING_CHAR = "<";
    public static $CLOSING_CHAR = ">";
    public static $EMPTYCLOSE_CHAR = " />";
    public static $OPENCLOSE_CHAR = "</";

    private function compose_text(Composable $element, $indent) {
        $markup = $element->content;

        return $indent . $markup;
    }

    private function compose_opening(Composable $element) {
        $opening = MarkupComposer::$OPENING_CHAR . $element->name();

        $attr_value = $element->attributes->all();
        $attr_markups = array();
        $attr_markup = "";

        foreach ($attr_value as $name=>$values) {
            $value = implode(" ", $values);
            $attr_markup = $name . '="' . $value . '"';
            array_push($attr_markups, $attr_markup);
        }

        $attribute = implode(" ", $attr_markups);

        if ($attribute == "") {
            $markup = $opening;
        } else {
            $markup = $opening . " " . $attribute;
        }

        return $markup;
    }

    private function compose_content(Composable $element, $child_level) {
        $content = "";
        $children = $element->children->all();

        foreach ($children as $child) {
            $content .= $this->compose($child, $child_level);
        }

        return $content;
    }

    public function compose(Composable $element, $indent_level) {
        $indent = str_repeat(MarkupComposer::$INDENT_UNIT, $indent_level);
        $schema = $element->schema();

        if ($schema == Composable::TEXT_ELEMENT_SCHEMA) {
            $composed = $this->compose_text($element, $indent);
        } else if ($schema == Composable::EMPTY_ELEMENT_SCHEMA) {
            $opening = $this->compose_opening($element);
            $closing = MarkupComposer::$EMPTYCLOSE_CHAR;

            $composed = $indent . $opening . $closing;
        } else if ($schema == Composable::PAIRED_ELEMENT_SCHEMA) {
            $opening = $this->compose_opening($element);

            if (count($element->attributes->all()) > 0) {
                $opening .= " " . MarkupComposer::$CLOSING_CHAR;
            } else {
                $opening .= MarkupComposer::$CLOSING_CHAR;
            }

            $child_level = $indent_level + 1;
            $content = $this->compose_content($element, $child_level);

            $closing = MarkupComposer::$OPENCLOSE_CHAR . $element->name();
            $closing .= MarkupComposer::$CLOSING_CHAR;

            $composed = "";

            $composed .= $indent . $opening . "\n";
            $composed .= $content;
            $composed .= $indent . $closing; 
        } else {
            echo "[MarkupComposer] Error: Unknown composite schema";
        }

        $composed .= "\n";

        return $composed;
    }
}
?>