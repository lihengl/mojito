<?php
require 'markup_elements.php';

session_start();
//session_regenerate_id(true); 

$_SESSION['user_id'] = 0;

$composer = new MarkupComposer();

$img = new ImgElement("img.jpg", "NOT FOUND");
$img->attributes->others->set("title", "chiehli and jean");

$tx1 = new TextElement("hello returned text one.");
$tx2 = new TextElement("hello returned text two.");
$lbr = new BrElement();

$pgh = new PElement();
$pgh->attributes->id->set("document");
$pgh->attributes->classes->set("main");
$pgh->attributes->classes->add("focus");
$pgh->children->add($tx1);
$pgh->children->add($lbr);
$pgh->children->add($tx2);

echo $composer->compose($img, 0);
echo $composer->compose($pgh, 0);

$_SESSION = array();

session_destroy();
?>