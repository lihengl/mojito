<?php
require 'empty_element.php';

session_start();
//session_regenerate_id(true); 

$_SESSION['user_id'] = 0;

$element = new EmptyElement("hr", 0);
$element->id_attribute = "break";
$element->class_attribute = array("breaker", "rock");

echo $element->compose();


$_SESSION = array();

session_destroy();
?>