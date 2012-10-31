<?php
require_once 'html_bases.php';

interface Labelable
{
    public function label_first();
}

class FormElement extends HtmlElement
{
    public static $TAGNAME = "form";

    public static $ACTIONATTR = "action";
    public static $METHODATTR = "method";
    public static $ENCTYPEATTR = "enctype";

    public static $GETMTHD = "get";
    public static $POSTMTHD = "post";
    public static $FILEENCTYPE = "multipart/form-data";

    public function __construct($action_handler) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$ACTIONATTR] = $action_handler;
        $this->attributes[self::$METHODATTR] = self::$GETMTHD;
        $this->attributes[self::$ENCTYPEATTR] = "";

        $this->children = array();        
    }

    public function set_action($handler_url) {
        $this->attributes[self::$ACTIONATTR] = $handler_url;
    }

    public function use_post() {
        $this->attributes[self::$METHODATTR] = self::$POSTMTHD;
    }

    public function set_encryption() {
        $this->attributes[self::$ENCTYPEATTR] = self::$FILEENCTYPE;
    }

    private function push_with_label(Labelable $input, $label_text) {
        $label = new LabelElement($input, $label_text);
        array_push($this->children, $label);
        return $input;        
    }

    public function push_textinput($name, $value, $label_text) {
        $input = new TextInputElement($this, $name, $value);
        return $this->push_with_label($input, $label_text);
    }

    public function push_passwordinput($name, $value, $label_text) {
        $input = new PasswordInputElement($this, $name, $value);
        return $this->push_with_label($input, $label_text);
    }

    public function push_radioinput($name, $value, $label_text) {
        $input = new RadioInputElement($this, $name, $value);
        return $this->push_with_label($input, $label_text);
    }

    public function push_checkboxinput($name, $value, $label_text) {
        $input = new CheckboxInputElement($this, $name, $value);
        return $this->push_with_label($input, $label_text);
    }

    public function push_selection($name, $label_text,
                                   $first_value, $first_text,
                                   $second_value, $second_text) {
        $selection = new SelectElement($this, $name,
                                       $first_value, $first_text,
                                       $second_value, $second_text);
        return $this->push_with_label($input, $label_text);
    }

    public function push_fileinput($name, $label_text) {
        if ($this->attributes[self::$METHODATTR] != self::$POSTMTHD) {
            echo "[FormElement] Error: method must be post to push file input";
            return NULL;
        } else if ($this->attributes[self::$ENCTYPEATTR] != self::$FILEENCTYPE) {
            echo "[FormElement] Error: invalid enctype to push file input";
            return NULL;
        } else {
            $input = new FileInputElement($this, $name);
            return $this->push_with_label($input, $label_text);
        }
    }

    public function push_submitinput($name, $value, $label_text) {
        $input = new SubmitInputElement($this, $name, $value);
        return $this->push_with_label($input, $label_text);
    }

    public function push_textarea($name, $value, $label_text) {
        $area = new TextareaElement($this, $name, $value);
        return $this->push_with_label($input, $label_text);
    }

    public function push_fieldset($legend_text) {
        $fieldset = new FieldsetElement($this, $legend_text);
        array_push($this->children, $fieldset);
        return $fieldset;
    }
}

class CheckboxInputElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "checkbox";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $CHECKATTR = "checked";

    public static $CHECKEDVALUE = "checked";

    public function __construct(FormElement $host, $name, $value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $value;
        $this->attributes[self::$CHECKATTR] = "";        

        $this->children = NULL;
    }

    public function label_first() {
        return FALSE;
    }

    public function check() {
        $this->attributes[self::$CHECKATTR] = self::$CHECKEDVALUE;
    }

    public function uncheck() {
        $this->attributes[self::$CHECKATTR] = "";
    }
}

class PasswordInputElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "password";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";
    
    public function __construct(FormElement $host, $name, $default_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $default_value;
        $this->attributes[self::$MAXLENGTHATTR] = "";

        $this->children = NULL;
    }

    public function label_first() {
        return TRUE;
    }    

    public function set_maxlength($integer_value) {
        $this->attriubutes[self::$MAXLENGTHATTR] = $integer_value;
    }
}

class RadioInputElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "radio";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $CHECKATTR = "checked";

    public static $CHECKEDVALUE = "checked";

    public function __construct(FormElement $host, $name, $value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $value;
        $this->attributes[self::$CHECKATTR] = "";

        $this->children = NULL;
    }

    public function label_first() {
        return FALSE;
    }

    public function check() {
        $this->attributes[self::$CHECKATTR] = self::$CHECKEDVALUE;
    }

    public function uncheck() {
        $this->attributes[self::$CHECKATTR] = "";
    }
}

class TextInputElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "text";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";
    public static $MAXLENGTHATTR = "maxlength";

    public function __construct(FormElement $host, $name, $default_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $default_value;
        $this->attributes[self::$MAXLENGTHATTR] = "";        

        $this->children = NULL;
    }

    public function label_first() {
        return TRUE;
    }    

    public function set_maxlength($integer_value) {
        $this->attributes[self::$MAXLENGTHATTR] = $integer_value;
    }
}

class OptionElement extends HtmlElement
{
    public static $TAGNAME = "option";

    public static $VALUEATTR = "value";
    public static $SELECTATTR = "selected";

    public static $SELECTEDVALUE = "selected";

    public function __construct(SelectElement $host, $value, $option_text) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$VALUEATTR] = $value;
        $this->attributes[self::$SELECTATTR] = "";

        $this->children = array();
        $text = new TextElement($option_text);
        array_push($this->children, $text);
    }

    public function select() {
        $this->attributes[self::$SELECTATTR] = self::$SELECTEDVALUE;
    }
}

class SelectElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "select";

    public static $NAMEATTR = "";

    public function __construct(FormElement $host, $name,
                                $value_first, $text_first,
                                $value_second, $text_second) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$NAMEATTR] = $name;
        
        $this->children = array();
        $option_first = new OptionElement($this, $value_first, $text_first);
        $option_second = new OptionElement($this, $value_second, $text_second);
        array_push($this->children, $option_first);
        array_push($this->children, $option_second);
    }

    public function label_first() {
        return TRUE;
    }

    public function select($option_index) {
        $selection = NULL;
        
        try {
            $selection = $this->children[$option_index];
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $selection->select();
    }

    public function push_option($value, $text) {
        $option = new OptionElement($this, $value, $text);
        array_push($this->children, $option);
    }
}

class FileInputElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "file";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";

    public function __construct(FormElement $host, $name) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = "";

        $this->children = NULL;
    }

    public function label_first() {
        return TRUE;
    }
}

class SubmitInputElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "input";
    public static $TYPENAME = "submit";

    public static $TYPEATTR = "type";
    public static $NAMEATTR = "name";
    public static $VALUEATTR = "value";

    public function __construct(FormElement $host, $name, $value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->attributes[self::$TYPEATTR] = self::$TYPENAME;
        $this->attributes[self::$NAMEATTR] = $name;
        $this->attributes[self::$VALUEATTR] = $value;

        $this->children = NULL;
    }

    public function label_first() {
        return TRUE;
    }
}

class TextareaElement extends HtmlElement implements Labelable
{
    public static $TAGNAME = "textarea";

    public function __construct(FormElement $host, $name, $init_value) {
        $this->name = self::$TAGNAME;

        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");

        $this->children = array();
        $initial_text = new TextElement($init_value);
        array_push($this->children, $initial_text);
    }

    // children of textarea has to be rendered without indent
    // because it will be accounted for layout of its content
    public function render($indent_unit, $indent_level) {
        $unindented_output = parent::render("", 0);
        return $unindented_output;
    }

    public function label_first() {
        return TRUE;
    }
}

class LabelElement extends HtmlElement
{
    public static $TAGNAME = "label";

    public function __construct(Labelable $input, $text) {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();

        $label_text = new TextElement($text);

        if ($input->label_first()) {
            array_push($this->children, $label_text);
            array_push($this->children, $input);
        } else {
            array_push($this->children, $input);
            array_push($this->children, $label_text);
        }
    }
}

class LegendElement extends HtmlElement
{
    public static $TAGNAME = "legend";

    public function __construct(FieldsetElement $host, $content_text) {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        
        $this->children = array();
        $text = new TextElement($content_text);
        array_push($this->children, $text);
    }
}

class FieldsetElement extends HtmlElement
{
    public static $TAGNAME = "fieldset";

    public function __construct(FormElement $host, $legend_text) {
        $this->name = self::$TAGNAME;
        $this->attributes = array(parent::$IDATTR=>"", parent::$CLASSATTR=>"");
        $this->children = array();

        if ($legend_text == "") {
            // fieldset with no legend
        } else {
            $legend = new LegendElement($this, $legend_text);
            array_push($this->children, $legend);
        }
    }
}
?>