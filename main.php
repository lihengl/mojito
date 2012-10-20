<?php
require 'markup_element.php';

session_start();
//session_regenerate_id(true); 

$_SESSION['user_id'] = 0;


$text = new TextElement("hello world!");

$markup = new HtmlElement("h1");
$markup->add_attribute("id", "lihengl");
$markup->add_child($text);

echo $markup->compose("    ", 0);

$_SESSION = array();

session_destroy();
?>