<?php 
//get info about movie for editing purposes
require_once('../../inc/config.php');

header('Content-Type: application/json');

if(!isset($_GET['id'])){
	http_response_code(400);
	echo json_encode(['error' => 'You have not sent a movie id']);
	die();
}
$movie_id = (int) $_GET['id'];

if($movie_id <= 0){
	http_response_code(400);
	echo json_encode(['error' => 'You have not sent a valid movie id']);
	die();
}

require_once(CONTROLLERS_PATH.'localhost_database_pg.php');

$movie_query = "SELECT title, theater_release, dvd_release, genre_id as movie_genre, pre_rating, post_rating from movies where id = $1;";

$db_manager = new AGED_PG_Database_Manager();
$con = $db_manager->get_database_connection_object();
pg_prepare($con, "add_edit_movie_query", $movie_query);
$result = pg_execute($con, "add_edit_movie_query", [$movie_id]);
pg_close($con);

$movie_array = $db_manager->get_array_from_result($result);
$movie_info = $movie_array[0] ?? null;

echo json_encode(['movie' => $movie_info]);
die();







