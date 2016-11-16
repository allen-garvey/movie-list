<?php 
require_once('../inc/config.php');
require_once(CONTROLLERS_PATH.'page_controller.php');

//routing
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//api routes
if(preg_match('#^/api#', $request_path)){
	header('Content-Type: application/json');
	if(preg_match('#^/api/activate#', $request_path) && $_SERVER['REQUEST_METHOD'] === 'POST'){
		include(CONTROLLERS_PATH.'activate_movies.php');
	}
	elseif(preg_match('#^/api/movies#', $request_path)){
		//GET gets info about a specific movie
		//POST updates or adds a movie
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			include(CONTROLLERS_PATH.'post_movies.php');
		}
		else{
			include(CONTROLLERS_PATH.'get_movies.php');
		}
	}

}
//page routes
else{
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
}


//if we're here no api routes match
http_response_code(404);

