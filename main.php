<?php
require 'markup_composer.php';

session_start();

$_SESSION['user_id'] = 0;

$composer = new MarkupComposer();

$_heading = "MOJITO";
$_title_text = "MOJITO - memory usage: ";
$_charset = "charset";
$_charset_val = "UTF-8";

$heading = new H1Element($_heading);

$body = new BodyElement();
$body->children->add($heading);

$title_content = $_title_text . memory_get_usage();
$title = new TitleElement($title_content);
$meta_char = new MetaElement($_charset, $_charset_val);

$head = new HeadElement();
$head->children->add($meta_char);
$head->children->add($title);

$html = new HtmlElement();
$html->children->add($head);
$html->children->add($body);

echo MarkupComposer::$HTML5_DOCTYPE . "\n";
echo $composer->compose($html, 0);

$_SESSION = array();

session_destroy();
?>