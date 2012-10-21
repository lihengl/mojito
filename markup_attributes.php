<?php
class MarkupAttributes
{
    public static $ID_NAME = "id";
    public static $CLASS_NAME = "class";

    private $id;
    private $classes;
    private $others;

    public function __construct() {
        $this->id = "";
        $this->classes = array();        
        $this->others = array();
    }

    private function set_classes($value) {
        $success = false;

        if (in_array($value, $this->classes)) {
            $success = false;
        } else {
            array_push($this->classes, $value);
            $success = true;
        }

        return $success;
    }

    private function set_others($name, $value) {
        $success = false;

        if (array_key_exists($name, $this->others)) {
            $success = false;
        } else {
            $success = true;
        }

        $this->others[$name] = $value;

        return $success;
    }

    public function add($name, $value) {
        $success = false;

        if ($name == MarkupAttributes::$ID_NAME) {
            $this->id = $value;
            $success = true;
        } else if ($name == MarkupAttributes::$CLASS_NAME) {
            $success = $this->set_classes($value);
        } else {
            $success = $this->set_others($name, $value);
        }

        return $success;
    }

    public function remove($name, $value) {
        // TODO
    }

    public function serialize() {
        $series = array();

        if ($this->id != "") {
            $id_series = MarkupAttributes::$ID_NAME . '="' . $this->id . '"';
            array_push($series, $id_series);
        }

        if (count($this->classes) > 0) {
            $class_string = implode(" ", $this->classes);
            $class_series = MarkupAttributes::$CLASS_NAME;
            $class_series .= '="' . $class_string . '"';
            array_push($series, $class_series);
        }

        if (count($this->others) > 0) {
            foreach ($this->others as $name=>$value) {
                $other_series = $name . '="' . $value . '"';
                array_push($series, $other_series);
            }
        }

        $serialized = implode(" ", $series);

        return $serialized;
    }
}
?>