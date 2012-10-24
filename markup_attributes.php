<?php
/*
 * attributes will be accessed through name
 * TODO: do we need the value here?
 *
 */
interface Decomposable
{
    public function name();
    public function value();
}

class AltAttribute implements Decomposable
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

class CharsetAttribute implements Decomposable
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

    public function all() {
        return $this->values;
    }
}

class HrefAttribute implements Decomposable
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
}

class RelAttribute implements Decomposable
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

class SrcAttribute implements Decomposable
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

class TypeAttribute implements Decomposable
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

class MarkupAttributes
{
    private $id;
    private $class;
    private $other;

    public function __construct() {
        $this->id = new IdAttribute();
        $this->class = new ClassAttribute();
        $this->other = array();
    }

    public function add_class($class_value) {
        $this->class->add($class_value);
    }

    public function is_class($class_value) {
        return in_array($class_value, $this->class);
    }

    public function add(Decomposable $attribute) {
        array_push($this->other, $attribute);
    }

    public function all() {
        /*
         * push attributes into name=>array(values) pair
         * this way we get a consistent format no matter
         * attributes have multiple values or not
         */
        $all_attrs = array();

        $id_value = $this->id->get();

        if ($id_value != "") {
            $all_attrs[IdAttribute::$NAME] = array($id_value);
        } else {
            // id attribute not specified, do not push it into the array
        }

        $class_values = $this->class->all();

        if (count($class_values) > 0) {
            $all_attrs[ClassAttribute::$NAME] = $class_values;
        } else {
            // class attribute not specified, do not push it into the array
        }

        $other_attrs = $this->other;

        if (count($other_attrs) > 0) {
            foreach ($other_attrs as $attribute) {
                $attr_name = $attribute->name();
                $attr_value = $attribute->value();
                $all_attrs[$attr_name] = array($attr_value);
            }
        } else {
            // no other all_attrs specified, push none into the array
        }

        return $all_attrs;
    }
}
?>