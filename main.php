<?php
require 'markup_elements.php';

session_start();
//session_regenerate_id(true); 

$_SESSION['user_id'] = 0;

$composer = new MarkupComposer();

$heading = new H1Element("MOJITO");

$body = new BodyElement();
$body->children->add($heading);

$title = new TitleElement("MOJITO");
$meta_char = new MetaElement("charset", "UTF-8");

$head = new HeadElement();
$head->children->add($meta_char);
$head->children->add($title);

$html = new HtmlElement();
$html->children->add($head);
$html->children->add($body);

echo "<!DOCTYPE html>\n";
echo $composer->compose($html, 0);

echo "memory usage: " . memory_get_usage();

$_SESSION = array();

session_destroy();
?>