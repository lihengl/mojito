<?php
require 'inline_elements.php';
require 'block_elements.php';
require 'form_elements.php';

$title = "memory usage: " . memory_get_usage();

$html = new HtmlElement($title);
$html->attach_style("layout.css");

$heading = new H1Element("MOJITO");
$html->body_push($heading);

// put test code here

// output to browser
echo "<!DOCTYPE html>\n";
echo $html->render("    ", 0);
?>