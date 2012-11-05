<?php
session_start();
require 'yf2etest2.php';

$test = new Yf2eTest2("Instant Search");

echo $test->render();
?>