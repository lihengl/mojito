<?php
require 'html_renderer.php';

echo HtmlRenderer::$DOCTYPE_MARKUP . "\n";

$body = new BodyElement();

$heading_text = "MOJITO";
$heading = new H1Element($heading_text);
$body->children->add($heading);

// put test code here

$title = "memory usage: " . memory_get_usage();
$head = new HeadElement($title);

$html = new HtmlElement($head, $body);
$renderer = new HtmlRenderer();

echo $renderer->render($html, 0);
?>