<?php
require 'html_renderer.php';

$charset = "UTF-8";
$title = "memory usage: " . memory_get_usage();

$html = new HtmlElement($charset, $title);

$heading_text = "THE FIRST INSURANCE CO., LTD.";
$heading = new H1Element($heading_text);
$html->body()->children->add($heading);

// put test code here

// output to browser
echo HtmlRenderer::$DOCTYPE_MARKUP . "\n";
echo HtmlRenderer::instance()->render($html, 0);
?>