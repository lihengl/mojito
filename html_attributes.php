<?php
class HtmlAttributes
{
    public static $ID = "id";
    public static $CLASS = "class";

    private $values;

    public function __construct($valid_names) {
        $this->values = array();

        array_push($this->values, HtmlAttributes::$ID=>"");
        array_push($this->values, HtmlAttributes::$CLASS=>"");

        foreach ($valid_names as $name) {
            array_push($this->values, $name=>"");
        }
    }

    public function get_id() {
        return $this->values[HtmlAttributes::$ID];
    }

    public function set_id($id_value) {
        $this->values[HtmlAttributes::$ID] = $id_value;
        return true;
    }

    public function get_class() {
        return $this->values[HtmlAttributes::$CLASS];
    }

    public function set_class($class_value) {
        $this->values[HtmlAttributes::$CLASS] = $class_value;
    }

    public function set($name, $value) {
        if (array_key_exists($name, $this->values)) {
            $this->values[$name] = $value;
            return true;
        } else {
            echo "[HtmlAttributes] Error: invalid attribute name";
            return false;
        }
    }

    public function get($name) {
        $value = "";
        if (array_key_exists($name, $this->values)) {
            $value = $this->values[$name];
        } else {
            echo "[HtmlAttributes] Error: invalid attribute name";
        }

        return $value;
    }

    public function names() {
        return array_keys($this->values);
    }
}
?>