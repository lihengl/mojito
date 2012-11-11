<?php
require 'yf2etest2.php';

if (isset($_GET['qchar'])) {
    echo Yf2eTest2::fetch($_GET['qchar']);
} else if(isset($_GET['search'])) {
    // TODO: figure out what to do here
    // This gets called if the user press enter in the search box
} else {
    $test = new Yf2eTest2("Yahoo! - F2E Test");    
    echo $test->render();
}
?>