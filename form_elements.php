<?php
require_once 'html_base.php';

interface Enlabelable
{
    public function enlabel($text);
}

class LabelElement extends HtmlBase
{
    public static $tag = "label";

    private $text;

    public function __construct($label_text) {
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->children = array();

        $this->text = new TextElement($label_text);
    }

    // decide which one to use based on desired label position
    public function put_ahead($input) {
        array_push($this->children, $this->text);
        array_push($this->children, $input);        
    }

    public function put_behind($input) {
        array_push($this->children, $input);
        array_push($this->children, $this->text);
    }
}

class TextInputElement extends HtmlBase implements Enlabelable
{
    public static $tag = "input";
    public static $type_value = "text";

    public static $type = "type";
    public static $name = "name";
    public static $value = "value";

    public static $maxlength = "maxlength";

    public function __construct(FormElement $host, $name, $default_value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->attributes[self::$type] = self::$type_value;
        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $default_value;
        
        $this->attributes[self::$maxlength] = "";        

        $this->children = NULL;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }

    public function max($integer_value) {
        $this->attributes[self::$maxlength] = $integer_value;
    }
}

class PasswordInputElement extends HtmlBase implements Enlabelable
{
    public static $tag = "input";
    public static $type_value = "password";

    public static $type = "type";
    public static $name = "name";
    public static $value = "value";

    public static $maxlength = "maxlength";
    
    public function __construct(FormElement $host, $name, $default_value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->attributes[self::$type] = self::$type_value;
        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $default_value;
        
        $this->attributes[self::$maxlength] = "";

        $this->children = NULL;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }

    public function max($integer_value) {
        $value = intval($integer_value);
        $this->attriubutes[self::$maxlength] = $value;
    }
}

class ButtonInputElement extends HtmlBase implements Enlabelable
{
    public static $tag = "input";
    public static $type_value = "button";

    public static $type = "type";
    public static $name = "name";
    public static $value = "value";

    public function __construct(FormElement $host, $name, $value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");

        $this->attributes[self::$type] = self::$type_value;
        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $value;

        $this->children = NULL;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_behind($this);
        return $label;
    }
}   

class CheckboxInputElement extends HtmlBase implements Enlabelable
{
    public static $tag = "input";
    public static $type_value = "checkbox";

    public static $type = "type";
    public static $name = "name";
    public static $value = "value";

    public static $checked = "checked";
    public static $checked_value = "checked";

    public function __construct(FormElement $host, $name, $value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");

        $this->attributes[self::$type] = self::$type_value;
        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $value;
        
        $this->attributes[self::$checked] = "";        

        $this->children = NULL;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_behind($this);
        return $label;
    }

    public function check() {
        $this->attributes[self::$checked] = self::$checked_value;
    }

    public function uncheck() {
        $this->attributes[self::$checked] = "";
    }
}

class RadioInputElement extends HtmlBase implements Enlabelable
{
    public static $tag = "input";
    public static $type_value = "radio";

    public static $type = "type";
    public static $name = "name";
    public static $value = "value";

    public static $checked = "checked";
    public static $checked_value = "checked";

    public function __construct(FormElement $host, $name, $value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->attributes[self::$type] = self::$type_value;
        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $value;
        
        $this->attributes[self::$checked] = "";

        $this->children = NULL;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_behind($this);
        return $label;
    }

    public function check() {
        $this->attributes[self::$checked] = self::$checked_value;
    }

    public function uncheck() {
        $this->attributes[self::$checked] = "";
    }
}

class HiddenInputElement extends HtmlBase implements Enlabelable
{
    public static $tag = "input";
    public static $type_value = "hidden";

    public static $type = "type";
    public static $name = "name";
    public static $value = "value";

    public function __construct(FormElement $host, $name, $value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$type] = self::$type_value;
        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $value;

        $this->children = NULL;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }
}

class SubmitInputElement extends HtmlBase implements Enlabelable
{
    public static $tag = "input";
    public static $type_value = "submit";

    public static $type = "type";
    public static $name = "name";
    public static $value = "value";

    public function __construct(FormElement $host, $name, $value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$type] = self::$type_value;
        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $value;

        $this->children = NULL;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }
}

class SelectElement extends HtmlBase implements Enlabelable
{
    public static $tag = "select";

    public static $name = "name";

    private $grouped;

    public function __construct(FormElement $host, $name, $options) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$name] = $name;
        
        $this->children = array();

        foreach ($options as $value=>$text) {
            $option = new OptionElement($this, $value, $text);
            array_push($this->children, $option);
        }

        $this->grouped = FALSE;
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }

    public function push_optgroup($groups) {
        $optgroup = NULL;

        if ($this->grouped === TRUE) {
            echo "[SelectElement] Error: selection already grouped";
        } else if (count($groups) < 0) {
            echo "[SelectElement] Error: number of groups must be positive";
        } else if (count($groups) > count($this->children)) {
            echo "[SelectElement] Error: number of groups must be smaller";
        } else {
            $group_index = 0;

            foreach ($groups as $label=>$count) {
                $optgroup = new OptgroupElement($this, $label);
                $set = array_splice($this->children, $group_index, $count, array($optgroup));
                $optgroup->push($set);

                $group_index += 1;
            }
        }

        $this->grouped = TRUE;
        
        return $optgroup;
    }    
}

class OptionElement extends HtmlBase
{
    public static $tag = "option";

    public static $value = "value";
    public static $selected = "selected";

    public static $selected_value = "selected";

    public function __construct(SelectElement $host, $value, $option_text) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$value] = $value;
        $this->attributes[self::$selected] = "";

        $this->children = array();
        $text = new TextElement($option_text);
        array_push($this->children, $text);
    }

    public function select() {
        $this->attributes[self::$selected] = self::$selected_value;
    }
}

class TextareaElement extends HtmlBase implements Enlabelable
{
    public static $tag = "textarea";

    public static $name = "name";

    public function __construct(FormElement $host, $name, $init_value) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$name] = $name;

        $this->children = array();
        $initial_text = new TextElement($init_value);
        array_push($this->children, $initial_text);
    }

    public function enlabel($text) {
        $label = new LabelElement($text);
        $label->put_ahead($this);
        return $label;
    }

    // override
    public function render($indent_unit, $indent_level) {
        $unindented_output = parent::render("", 0);
        return $unindented_output;
    }    
}

class FormElement extends HtmlBase
{
    public static $tag = "form";

    public static $action = "action";
    public static $method = "method";
    public static $enctype = "enctype";

    public static $getmthd_value = "get";
    public static $postmthd_value = "post";
    public static $fileenc_value = "multipart/form-data";

    public function __construct($handler_url) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$action] = $handler_url;
        $this->attributes[self::$method] = self::$getmthd_value;
        $this->attributes[self::$enctype] = "";

        $this->children = array();
    }

    public function use_post() {
        $this->attributes[self::$method] = self::$postmthd_value;
    }

    public function file_encrypt() {
        $this->attributes[self::$enctype] = self::$fileenc_value;
    }

    public function push_textinput($name, $value, $instruction, $masked) {
        $textinput = NULL;
        
        if ($masked === TRUE) {
            $textinput = new PasswordInputElement($this, $name, $value);
        } else {
            $textinput = new TextInputElement($this, $name, $value);
        }
        
        $labeled = $textinput->enlabel($instruction);
        array_push($this->children, $labeled);

        return $textinput;
    }

    public function push_checkbox($name, $value, $instruction) {
        $checkbox = new CheckboxInputElement($this, $name, $value);
        $labeled = $checkbox->enlabel($instruction);
        
        array_push($this->children, $labeled);
        
        return $checkbox;
    }    

    public function push_radioinput($name, $value, $instruction) {
        $radioinput = new RadioInputElement($this, $name, $value);
        $labeled = $radioinput->enlabel($instruction);

        array_push($this->children, $labeled);
        
        return $radioinput;
    }

    public function push_selection($name, $options, $instruction) {
        $selection = NULL;

        if (count($options) < 2) {
            echo "[FormElement] Error: options must be than two";
        } else {
            $selection = new SelectElement($this, $name, $options);
            $labeled = $selection->enlabel($instruction);
            array_push($this->children, $labeled);          
        }
        
        return $selection;
    }

    public function push_fileinput($name, $instruction) {
        $fileinput = NULL;

        if ($this->attributes[self::$method] != self::$postmthd_value) {
            echo "[FormElement] Error: method must be post to push file input";
        } else if ($this->attributes[self::$enctype] != self::$fileenc_value) {
            echo "[FormElement] Error: invalid enctype to push file input";
        } else {
            $fileinput = new FileInputElement($this, $name);
            $labeled = $fileinput->enlabel($instruction);
            array_push($this->children, $labeled);
        }

        return $fileinput;
    }

    public function push_hiddeninput($name, $value, $instruction) {
        $hiddeninput = new HiddenInputElement($this, $name, $value);
        $labeled = $hiddeninput->enlabel($instruction);

        array_push($this->children, $labeled);

        return $hiddeninput;
    }

    public function push_buttoninput($name, $value, $instruction) {
        $buttoninput = new ButtonInputElement($this, $name, $value);
        $labeled = $buttoninput->enlabel($instruction);
        
        array_push($this->children, $labeled);
        
        return $buttoninput;        
    }

    public function push_submitinput($name, $value, $instruction) {
        $submitinput = new SubmitInputElement($this, $name, $value);
        $labeled = $submitinput->enlabel($instruction);
        
        array_push($this->children, $labeled);
        
        return $submitinput;
    }

    public function push_textarea($name, $value, $instruction) {
        $textarea = new TextareaElement($this, $name, $value);
        $labeled = $textarea->enlabel($instruction);
        
        array_push($this->children, $labeled);
        
        return $textarea;
    }    

    public function push_fieldset($description, $begin, $count) {
        $fieldset = NULL;

        if ($begin < 0) {
            echo "[FormElement] Error: begin must be positive";
        } else if ($begin >= count($this->children)) {
            echo "[FormElement] Error: begin must be smaller";
        } else if ($count <= 0) {
            echo "[FormElement] Error: count must be positive";
        } else {
            $fieldset = new FieldsetElement($this, $description);
            $set = array_splice($this->children, $begin, $count, array($fieldset));
            $fieldset->push($set);
        }
        
        return $fieldset;
    }
}

class OptgroupElement extends HtmlBase
{
    public static $tag = "optgroup";

    public static $label = "label";
    public static $disabled = "disabled";

    public static $disabled_value = "disabled";

    public function __construct(SelectElement $host, $label_text) {
        $this->tagname = self::$tag;

        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->attributes[self::$label] = $label_text;
        $this->attributes[self::$disabled] = "";
        
        $this->children = array();
    }

    public function disable() {
        $this->attributes[self::$disabled] = self::$disabled_value;        
    }

    public function push($options) {
        foreach ($options as $option) {
            array_push($this->children, $option);
        }
    }
}

class LegendElement extends HtmlBase
{
    public static $tag = "legend";

    public function __construct(FieldsetElement $host, $content_text) {
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        
        $this->children = array();
        $text = new TextElement($content_text);
        array_push($this->children, $text);
    }
}

class FieldsetElement extends HtmlBase
{
    public static $tag = "fieldset";

    public function __construct(FormElement $host, $legend_text) {
        $this->tagname = self::$tag;
        $this->attributes = array(parent::$id=>"", parent::$class=>"");
        $this->children = array();

        if ($legend_text == "") {
            // fieldset with no legend
        } else {
            $legend = new LegendElement($this, $legend_text);
            array_push($this->children, $legend);
        }
    }

    public function push($inputs) {
        foreach ($inputs as $input) {
            array_push($this->children, $input);
        }
    }
}
?>