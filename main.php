<?php
require 'html_elements.php';

$title = "memory usage: " . memory_get_usage();

$html = new HtmlElement($title);

$heading_text = "THE FIRST INSURANCE CO., LTD.";
$heading = new H1Element($heading_text);
$html->body_push($heading);

// put test code here

// output to browser
echo $html->render("    ", 0);
?>