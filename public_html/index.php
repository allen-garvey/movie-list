<?php 
require_once('../inc/config.php');
require_once(CONTROLLERS_PATH.'page_controller.php');

//routing
$path = array_key_exists('path', $_GET) ? $_GET['path'] : '';
if(preg_match('/^suggestions/', $path)){
	$page_controller = new AGED_Suggestions_Controller();
}
else if(preg_match('/^rated/', $path)){
	$page_controller = new AGED_Rated_Controller();
}
else{
	$page_controller = new AGED_Index_Controller();	
}

$movie_genre_result = AGED_Page_Controller::get_movie_genre_result();

include(VIEWS_PATH.'layout.php');