<?php 
//POST activates or deactivates specific movie
require_once('../../inc/config.php');
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	include(CONTROLLERS_PATH.'activate_movies.php');
}
else{
	http_response_code(400);
	echo json_encode(['error' => "Bad request: route requires POST"]);
}
die();







