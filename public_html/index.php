<?php 
require_once('../inc/config.php');
require_once(CONTROLLERS_PATH.'page_controller.php');

//routing
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if(preg_match('#^/suggestions#', $request_path)){
	$page_controller = new AGED_Suggestions_Controller();
}
else if(preg_match('#^/rated#', $request_path)){
	$page_controller = new AGED_Rated_Controller();
}
else{
	$page_controller = new AGED_Index_Controller();	
}

//required for add movie modal
$movie_genre_result = AGED_Page_Controller::get_movie_genre_result();

include(VIEWS_PATH.'layout.php');