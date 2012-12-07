<?php
require_once "html_elements.php";

$title = "Mojito Framework";
$heading = "MOJITO";
$description = "web framework with php, mysql and javascript";

$html = new HtmlElement($title);

$html->body_push(new H1Element($heading));
$html->body_push(new PElement($description));

$html->render();
?>