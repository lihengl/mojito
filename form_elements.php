<?php
require_once 'base_elements.php';

interface Enlabelable
{
    public function enlabel($text);
}

class LabelElement extends HtmlBase
{
    private static $tag = "label";

    private $text;

    public function __construct($label_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->text = new TextElement($label_text);
        // do not push this into the child yet
    }

    public function put_ahead($input) {
        array_push($this->children, $this->text);
        array_push($this->children, $input);        
    }

    public function put_behind($input) {
        array_push($this->children, $input);
        array_push($this->children, $this->text);
    }
}

class InputElement extends HtmlBase implements Enlabelable
{
    public static $TextType = "text";
    public static $PasswordType = "password";
    public static $RadioType = "radio";
    public static $CheckboxType = "checkbox";
    public static $FileType = "file";
    public static $ButtonType = "button";
    public static $HiddenType = "hidden";
    public static $SubmitType = "submit";

    private static $tag = "input";

    private static $type = "type";
    private static $name = "name";
    private static $value = "value";

    private static $maxlength = "maxlength";
    private static $checked = "checked";
    private static $placeholder = "placeholder";

    private static $checked_value = "checked";

    public function __construct(FormElement $host, $type, $name, $value) {
        parent::__construct(self::$tag);
        $this->children = NULL;

        if ($type == self::$TextType) {
            $this->attributes[self::$type] = self::$TextType;
        } else if ($type == self::$PasswordType) {
            $this->attributes[self::$type] = self::$PasswordType;
        } else if ($type == self::$RadioType) {
            $this->attributes[self::$type] = self::$RadioType;
        } else if ($type == self::$CheckboxType) {
            $this->attributes[self::$type] = self::$CheckboxType;
        } else if ($type == self::$FileType) {
            $host->use_post();
            $host->encrypt();
            $this->attributes[self::$type] = self::$FileType;
        } else if ($type == self::$ButtonType) {
            $this->attributes[self::$type] = self::$ButtonType;
        } else if ($type == self::$HiddenType) {
            $this->attributes[self::$type] = self::$HiddenType;
        } else if ($type == self::$SubmitType) {
            $this->attributes[self::$type] = self::$SubmitType;
        } else {
            echo "[InputElement] Error: invalid input type";
            $this->attributes[self::$type] = self::$HiddenType;            
        }

        $this->attributes[self::$name] = $name;
        $this->attributes[self::$value] = $value;        
        
        $this->attributes[self::$maxlength] = "";
        $this->attributes[self::$checked] = "";
    }

    private function texttype() {
        $type = $this->attributes[self::$type];
        
        if ($type == self::$TextType || $type == self::$PasswordType) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function checktype() {
        $type = $this->attributes[self::$type];
        
        if ($type == self::$RadioType || $type == self::$CheckboxType) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function enlabel($text) {
        $label = new LabelElement($text);

        if ($this->checktype() === TRUE) {
            $label->put_behind($this);            
        } else {
            $label->put_ahead($this);            
        }

        return $label;
    }    

    public function maxlength($value = NULL) {
        if ($this->texttype() === TRUE) {
            return $this->attribute(self::$maxlength, $value);
        } else {
            // do nothing
        }
    }

    public function check() {
        if ($this->checktype() === TRUE) {
            $this->attribute[self::$checked] = self::$checked_value;
        } else {
            // do nothing
        }
    }

    public function placeholder($text) {
        if ($this->texttype() === TRUE) {
            $this->attributes[self::$placeholder] = $text;
        } else {
            // do nothing
        }
    }
}

class SelectElement extends HtmlBase implements Enlabelable
{
    private static $tag = "select";

    private static $name = "name";

    private $grouped;

    public function __construct(FormElement $host, $name, $options) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$name] = $name;

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

class TextareaElement extends HtmlBase implements Enlabelable
{
    private static $tag = "textarea";

    private static $name = "name";

    public function __construct(FormElement $host, $name, $init_value) {
        parent::__construct(self::$tag);
        $this->children = array();  
        
        $this->attributes[self::$name] = $name;

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
    private static $tag = "form";

    private static $action = "action";
    private static $method = "method";
    private static $enctype = "enctype";

    private static $getmthd_value = "get";
    private static $postmthd_value = "post";
    private static $fileenc_value = "multipart/form-data";

    public function __construct($handler_url) {
        parent::__construct(self::$tag);
        $this->children = array();        

        $this->attributes[self::$action] = $handler_url;
        $this->attributes[self::$method] = self::$getmthd_value;
        $this->attributes[self::$enctype] = "";
    }

    public function use_post() {
        $this->attributes[self::$method] = self::$postmthd_value;
    }

    public function encrypt() {
        $this->attributes[self::$enctype] = self::$fileenc_value;
    }

    private function push_labeled(Enlabelable $input, $label_text) {
        $labeled = $input->enlabel($label_text);
        array_push($this->children, $labeled);
        return $labeled;        
    }

    public function push_input($type, $name, $value, $label_text) {
        $input = new InputElement($this, $type, $name, $value);
        $labeled = $this->push_labeled($input, $label_text);
        return $input;
    }

    public function push_textarea($name, $value, $instruction) {
        $textarea = new TextareaElement($this, $name, $value);
        $labeled = $this->push_labeled($textarea, $instruction);
        return $textarea;
    }

    public function push_selection($name, $options, $instruction) {
        $selection = NULL;

        if (count($options) < 2) {
            echo "[FormElement] Error: options must be than two";
        } else {
            $selection = new SelectElement($this, $name, $options);
            $labeled = $this->push_labeled($selection, $instruction);
        }
        
        return $selection;
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

class OptionElement extends HtmlBase
{
    private static $tag = "option";

    private static $value = "value";
    private static $selected = "selected";

    private static $selected_value = "selected";

    public function __construct(SelectElement $host, $value, $option_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$value] = $value;
        $this->attributes[self::$selected] = "";

        $text = new TextElement($option_text);
        array_push($this->children, $text);
    }

    public function select() {
        $this->attributes[self::$selected] = self::$selected_value;
    }
}

class OptgroupElement extends HtmlBase
{
    private static $tag = "optgroup";

    private static $label = "label";
    private static $disabled = "disabled";

    private static $disabled_value = "disabled";

    public function __construct(SelectElement $host, $label_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $this->attributes[self::$label] = $label_text;
        $this->attributes[self::$disabled] = "";
    }

    public function disable($do) {
        if ($do === TRUE) {
            $this->attributes[self::$disabled] = self::$disabled_value;
        } else {
            $this->attributes[self::$disabled] = "";            
        }
    }

    public function push($options) {
        foreach ($options as $option) {
            array_push($this->children, $option);
        }
    }
}

class LegendElement extends HtmlBase
{
    private static $tag = "legend";

    public function __construct(FieldsetElement $host, $content_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        $text = new TextElement($content_text);
        array_push($this->children, $text);
    }
}

class FieldsetElement extends HtmlBase
{
    private static $tag = "fieldset";

    public function __construct(FormElement $host, $legend_text) {
        parent::__construct(self::$tag);
        $this->children = array();

        if ($legend_text == "") {
            // this is a fieldset without legend
        } else {
            $legend = new LegendElement($this, $legend_text);
            array_push($this->children, $legned);
        }
    }

    public function push($inputs) {
        foreach ($inputs as $input) {
            array_push($this->children, $input);
        }
    }
}
?>