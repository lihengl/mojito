<?php
require 'html_renderer.php';

$title = "memory usage: " . memory_get_usage();

$html = new HtmlElement("UTF-8", $title);

$heading_text = "THE FIRST INSURANCE CO., <LTD.>";
$heading = new H1Element($heading_text);
$heading->set_classes(array("test", "ahead"));
$heading->set_id("identifier");
$html->body()->children->add($heading);

// put test code here

// output to browser
$renderer = new HtmlRenderer();
echo HtmlRenderer::$DOCTYPE_MARKUP . "\n";
echo $renderer->render($html, 0);
?>