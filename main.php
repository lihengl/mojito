<?php
require 'markup_element.php';

session_start();
//session_regenerate_id(true); 

$_SESSION['user_id'] = 0;


$text_first = new TextElement("This is text content one.");
$markup_break = new HtmlElement("br");
$text_second = new TextElement("This is text content two.");
$markup_paragraph = new HtmlElement("p");

$markup_paragraph->add_child($text_first);
$markup_paragraph->add_child($markup_break);
$markup_paragraph->add_child($text_second);

$markup_paragraph->attributes->add("id", "yukuan");
$markup_paragraph->attributes->add("class", "ycombinator");
$markup_paragraph->attributes->add("class", "summer12");
$markup_paragraph->attributes->add("alt", "google");

echo $markup_paragraph->compose("    ", 0);

$_SESSION = array();

session_destroy();
?>