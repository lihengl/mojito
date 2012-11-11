<?php
require 'yf2etest2.php';

if (isset($_GET['ajax_query'])) {
    $results = Yf2eTest2::Fetch($_GET['ajax_query']);
    echo json_encode($results);
} else if(isset($_GET['search'])) {
    // TODO: figure out what to do here
    // This gets called if the user press enter in the search box
} else {
    $test = new Yf2eTest2("Yahoo! - F2E Test");    
    echo $test->render();
}
?>