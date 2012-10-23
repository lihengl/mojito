<?php
require 'markup_elements.php';

session_start();

$_SESSION['user_id'] = 0;

$composer = new MarkupComposer();

$_heading = "MOJITO";
$_title_text = "MOJITO - memory usage: ";
$_src = "http://sphotos-d.ak.fbcdn.net/hphotos-ak-snc7/296_21167094371_8606_n.jpg";
$_alt = "Hsu-Chuan Tai";
$_charset = "charset";
$_charset_val = "UTF-8";

$heading = new H1Element($_heading);
$img = new ImgElement($_src, $_alt);

$body = new BodyElement();
$body->children->add($heading);
$body->children->add($img);

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