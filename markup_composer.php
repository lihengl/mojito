<?php
interface Composable
{
    const TEXT_SCHEMA = "text_schema";
    const EMPTY_SCHEMA = "empty_schema";
    const PAIRED_SCHEMA = "paired_schema";    

    public function name();
    public function schema();
}

interface IndentedComposer
{
    public function indent_compose(Composable $element, $indent);
}

class TextComposer implements IndentedComposer
{
    public function indent_compose(Composable $element, $indent) {
        $composed_text = $indent . $element->content;

        return $composed_text;
    }
}

class OpeningComposer implements IndentedComposer
{
    public function indent_compose(Composable $element, $indent) {
        $composed_opening = $indent . "<" . $element->name();

        $id_composed = $element->attributes->id->compose();
        
        if ($id_composed != "") {
            $composed_opening .= " " . $id_composed;
        } else {
            // id attribute not specified, do nothing
        }

        $classes_composed = $element->attributes->classes->compose();
        
        if ($classes_composed != "") {
            $composed_opening .= " " . $classes_composed;
        } else {
            // class attribute not specified, do nothing
        }

        $misc_composed = $element->attributes->others->compose();

        if ($misc_composed != "") {
            $composed_opening .= " " . $misc_composed;
        } else {
            // no misc attributes, do nothing
        }

        return $composed_opening;  
    }
}

class ClosingComposer implements IndentedComposer
{
    public function indent_compose(Composable $element, $indent) {
        $composed_closing = $indent . "</" . $element->name() . ">";

        return $composed_closing;
    }
}

class MarkupComposer
{
    public static $INDENT_UNIT = "    ";

    private $text_composer;
    private $opening_composer;
    private $closing_composer;

    public function __construct() {
        $this->text_composer = new TextComposer();
        $this->opening_composer = new OpeningComposer();
        $this->closing_composer = new ClosingComposer();
    }

    public function compose(Composable $element, $indent_level) {
        $indent = str_repeat(MarkupComposer::$INDENT_UNIT, $indent_level);
        $schema = $element->schema();

        $composition = "";
        $composer = NULL;

        if ($schema == Composable::TEXT_SCHEMA) {
            $composer = $this->text_composer;
            $composition = $composer->indent_compose($element, $indent);
        } else if ($schema == Composable::EMPTY_SCHEMA) {
            $composer = $this->opening_composer;
            $composition = $composer->indent_compose($element, $indent);
            $composition .= " />";            
        } else if ($schema == Composable::PAIRED_SCHEMA) {
            $composer = $this->opening_composer;
            $composition = $composer->indent_compose($element, $indent);
            $composition .= ">\n";

            $child_level = $indent_level + 1;
            $children = $element->children->all();
            
            foreach ($children as $child_element) {
                $composition .= $this->compose($child_element, $child_level);
            }
 
            $composer = $this->closing_composer;
            $composition .= $composer->indent_compose($element, $indent);            
        } else {
            echo "[MarkupComposer] Error: Unknown composite schema";
        }

        $composition .= "\n";

        return $composition;
    }
}
?>