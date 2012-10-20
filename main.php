<?php
require 'empty_element.php';

session_start();
//session_regenerate_id(true); 

$_SESSION['user_id'] = 0;

$element = new EmptyElement("hr");
$element->indent_level = 4;
$element->attributes = array("id"=>array("weibar"), "class"=>array("girl", "hot"));

echo $element->compose();


$_SESSION = array();

session_destroy();
?>