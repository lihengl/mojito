<?php
session_start();
require 'yf2etest2.php';

$test = new Yf2eTest2("Instant Search");

if (isset($_GET['query'])) {
    echo $test->fetch($_GET['query']);
} else {
    echo $test->render();
}
?>