<?php
interface Hashable
{
    public function name();
    public function value();
}

class IdAttribute
{
    public static $NAME = "id";

    public $value;

    public function __construct() {
        $this->value = "";
    }
}

class ClassAttribute
{
    public static $NAME = "class";

    public $values;

    public function __construct() {
        $this->values = array();
    }
}

class ActionAttribute implements Hashable
{
    public static $NAME = "action";

    private $value;

    public function __construct($handler_url) {
        $this->value = $handler_url;
    }

    public function name() {
        return ActionAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }
}

class AltAttribute implements Hashable
{
    public static $NAME = "alt";

    private $value;

    public function __construct($alt_value) {
        $this->value = $alt_value;
    }

    public function name() {
        return AltAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }    
}

class CharsetAttribute implements Hashable
{
    public static $NAME = "charset";

    private $value;

    public function __construct($charset_value) {
        $this->value = $charset_value;
    }

    public function name() {
        return CharsetAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }    
}

class HrefAttribute implements Hashable
{
    public static $NAME = "href";

    private $value;

    public function __construct($href_value) {
        $this->value = $href_value;
    }

    public function name() {
        return HrefAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }    
}

class RelAttribute implements Hashable
{
    public static $NAME = "rel";

    private $value;

    public function __construct($rel_value) {
        $this->value = $rel_value;
    }

    public function name() {
        return RelAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }    
}

class SrcAttribute implements Hashable
{
    public static $NAME = "src";

    private $value;

    public function __construct($src_value) {
        $this->value = $src_value;
    }

    public function name() {
        return SrcAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }    
}

class TitleAttribute implements Hashable
{
    public static $NAME = "title";

    private $value;

    public function __construct($title_value) {
        $this->value = $title_value;
    }

    public function name() {
        return TitleAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }    
}

class TypeAttribute implements Hashable
{
    public static $NAME = "type";

    private $value;

    public function __construct($type_value) {
        $this->value = $type_value;
    }

    public function name() {
        return TypeAttribute::$NAME;
    }

    public function value() {
        return $this->value;
    }    
}

class HtmlAttributes
{
    private $id;
    private $class;
    private $other;

    public function __construct() {
        $this->id = new IdAttribute();
        $this->class = new ClassAttribute();
        $this->other = array();
    }

    public function get_id() {
        return $this->id->value;
    }

    public function set_id($id_value) {
        $this->id->value = $id_value;
    }

    public function is_class($class_value) {
        return in_array($class_value, $this->class);
    }

    public function get_classes() {
        return $this->class->values;
    }

    public function add_class($value_to_add) {
        $classes = $this->class->values;

        if (strpos($value_to_add, " ")) {
            return false;
        } else if (in_array($value_to_add, $classes)) {
            return true;
        } else {
            array_push($this->class->values, $value_to_add);
            return true;
        }
    }

    public function add(Hashable $attribute) {
        $adding_name = $attribute->name();
        $adding_value = $attribute->value();

        foreach ($this->other as $existing) {
            $existing_name = $existing->name();

            if ($adding_name == $existing_name) {
                $existing->value = $adding_value;
                return false;
            }
        }

        array_push($this->other, $attribute);
        return true;
    }

    public function get($target_name) {
        $value = "";

        foreach ($this->other as $attribute) {
            if ($attribute->name() == $target_name) {
                return $attribute->value();
            }
        }

        return $value;
    }

    public function names() {
        $all_names = array();

        $id_value = $this->id->value;

        if ($this->id->value != "") {
            array_push($all_names, IdAttribute::$NAME);
        } else {
            // id not specified, do not push its name into the array
        }

        if (count($this->class->values) > 0) {
            array_push($all_names, ClassAttribute::$NAME);
        } else {
            // class not specified, do not push it into the array
        }
        
        foreach ($this->other as $attribute) {
            $name = $attribute->name();
            array_push($all_names, $name);
        }

        return $all_names;
    }
}
?>