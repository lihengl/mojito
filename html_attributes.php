<?php
// the class contains an array used as ordered key-value paired dictionary
// the keys are valid attribute names and the values are values for that name
// values are stored as strings - even for class
// this makes the values directly renderable
class HtmlAttributes
{
    public static $ID = "id";
    public static $CLASS = "class";

    public static $VALUE_SEPARATOR = " ";

    private $valid_name_values;

    public function __construct($valid_names) {
        // this is an array of name-value pairs
        // each name is an attribute name that is valid for belonging element
        $this->valid_name_values = array();

        // every element should be able to have id and class attribute
        // put them as the first two entries of the valid names array
        $this->valid_name_values[HtmlAttributes::$ID] = "";
        $this->valid_name_values[HtmlAttributes::$CLASS] = "";

        // have all the valid attribute names first
        // the values will be optionally filled in through belonging element 
        foreach ($valid_names as $name) {
            $this->valid_name_values[$name] = "";
        }
    }

    public function get_id() {
        return $this->valid_name_values[HtmlAttributes::$ID];
    }

    public function set_id($id_value) {
        $this->valid_name_values[HtmlAttributes::$ID] = $id_value;
        return true;
    }

    public function get_class() {
        return $this->valid_name_values[HtmlAttributes::$CLASS];
    }

    // takes an array of class values and overwrites current classes with it
    public function set_classes($class_values) {
        $combined = implode(HtmlAttributes::$VALUE_SEPARATOR, $class_values);
        $this->valid_name_values[HtmlAttributes::$CLASS] = $combined;
    }

    public function get($name) {
        $value = "";

        if (array_key_exists($name, $this->valid_name_values)) {
            $value = $this->valid_name_values[$name];
        } else {
            echo "[HtmlAttributes] Error: invalid attribute name";
        }

        return $value;
    }    

    public function set($name, $value) {

        if (array_key_exists($name, $this->valid_name_values)) {
            $this->valid_name_values[$name] = $value;
            return true;
        } else {
            echo "[HtmlAttributes] Error: invalid attribute name";
            return false;
        }

    }

    public function names() {
        return array_keys($this->valid_name_values);
    }

    public function all_specified() {
        $specified = array();

        foreach($this->valid_name_values as $name=>$value) {

            if ($value != "") {
                $specified[$name] = $value;
            } else {
                // ignore attribute name with no value specified
            }

        }

        return $specified; 
    }
}
?>