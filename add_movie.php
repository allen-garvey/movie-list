<?php 
include_once 'functions.php';
header('Content-Type: application/json');

$title = $_REQUEST["title"];

$pre_rating = sanitized_rating($_REQUEST["pre_rating"]);


add_to_database($title, $pre_rating);


$result = array();
$result['center'] = get_index_center_div();

echo json_encode($result);




?>