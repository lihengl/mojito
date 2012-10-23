<?php
class IdAttribute
{
    public static $NAME = "id";

    private $value;

    public function __construct() {
        $this->value = "";
    }

    public function set($id_value) {
        $this->value = $id_value;
    }

    public function get() {
        return $this->value;
    }

    public function compose() {
        if ($this->value == "") {
            return "";
        } else {
            return IdAttribute::$NAME . '="' . $this->value . '"';
        }
    }
}

class ClassAttribute
{
    public static $NAME = "class";

    private $values;

    public function __construct() {
        $this->values = array();
    }

    public function set($class_value) {
        $this->values = array($class_value);
    }

    public function add($class_value) {
        if (in_array($class_value, $this->values)) {
            // value already exists, do nothing
        } else {
            array_push($this->values, $class_value);
        }
    }

    public function compose() {
        if (count($this->values) == 0) {
            return "";
        } else {
            $to_string = implode(" ", $this->values);
            return ClassAttribute::$NAME . '="' . $to_string . '"';
        }
    }
}

class OtherAttributes
{
    private $values;

    public function __construct() {
        $this->values = array();
    }

    public function set($name, $value) {
        $this->values[$name] = $value;
    }

    public function compose() {
        if (count($this->values) == 0) {
            return "";
        } else {
            $to_array = array();
            $to_string = "";
            foreach ($this->values as $name=>$value) {
                $to_string = $name . '="' . $value . '"';
                array_push($to_array, $to_string);
            }
            return implode(" ", $to_array);
        }
    }
}

class MarkupAttributes
{
    public $id;
    public $classes;
    public $others;

    public function __construct() {
        $this->id = new IdAttribute();
        $this->classes = new ClassAttribute();
        $this->others = new OtherAttributes();
    }
}
?>