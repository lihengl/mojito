<?php
require 'markup_elements.php';

session_start();
//session_regenerate_id(true); 

$_SESSION['user_id'] = 0;

$composer = new MarkupComposer();

$heading = new H1Element("貨物運輸保險理賠案例");

$img = new ImgElement("img.png", "NOT FOUND");
$img->attributes->others->set("title", "chiehli and jean");
$img->attributes->id->set("girls");

$tx1 = new TextElement("中文中文中文");
$tx2 = new TextElement("Englih English English");
$lbr = new BrElement();

$pgh = new PElement();
$pgh->attributes->id->set("document");
$pgh->attributes->classes->set("main");
$pgh->attributes->classes->add("focus");
$pgh->children->add($tx1);
$pgh->children->add($lbr);
$pgh->children->add($tx2);

$script = new ScriptElement("jscript.js");

$body = new BodyElement();
$body->children->add($heading);
$body->children->add($img);
$body->children->add($pgh);
$body->children->add($script);

$link = new LinkElement("style.css", "text/css", "stylesheet");
$title = new TitleElement("testdrive");
$meta = new MetaElement("charset", "UTF-8");
$head = new HeadElement();
$head->children->add($meta);
$head->children->add($title);
$head->children->add($link);

$html = new HtmlElement();
$html->children->add($head);
$html->children->add($body);

echo "<!DOCTYPE html>\n";
echo $composer->compose($html, 0);

$_SESSION = array();

session_destroy();
?>