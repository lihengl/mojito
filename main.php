<?php
require 'html_renderer.php';

echo HtmlRenderer::$DOCTYPE_MARKUP . "\n";

$title = "MOJITO - memory usage: " . memory_get_usage();

$html = new HtmlElement();
$head = new HeadElement($title);
$body = new BodyElement();

$html->children->add($head);
$html->children->add($body);

$heading_text = "MOJITO";
$agent = $_SERVER['HTTP_USER_AGENT'];

$paragraph = new PElement($agent);
$heading = new H1Element($heading_text);

$body->children->add($paragraph);
$body->children->add($heading);

// put test code here

$renderer = new HtmlRenderer();

echo $renderer->render($html, 0);
?>