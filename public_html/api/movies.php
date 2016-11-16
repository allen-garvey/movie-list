<?php 
//GET gets info about a specific movie
//POST updates or adds a movie
require_once('../../inc/config.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	include(CONTROLLERS_PATH.'post_movies.php');
}
else{
	include(CONTROLLERS_PATH.'get_movies.php');
}
die();







