<?php
require_once "html_elements.php";

$doctitle = "testsite";
$heading = "BronteTe";
$line_1 = "this is going to be the first line of text";
$line_2 = "this is going to be a second line, yyaa";

$html = new HtmlElement($doctitle);

$html->body_push(new H1Element($heading));
$html->body_push(new PElement($line_1));
$html->body_push(new PElement($line_2));

$html->render();
?>