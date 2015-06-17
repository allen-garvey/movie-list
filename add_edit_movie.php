<?php 
require_once('inc/config.php');

if($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['movie'])) {
	header('Location: ' . HOME_URL );
	die();
}

require_once(INC_PATH . 'constants.php');
require_once(CONTROLLERS_PATH.'movie_data_validator.php');
require_once(CONTROLLERS_PATH.'localhost_database_pg.php');

$movie = json_decode($_POST['movie'], true);
$validator = new MovieDataValidator($movie);
$errors = [];
$prepared_statement_array = [];
$add_movie_keys = Movie_List_Constants::$add_movie_keys;
$edit_movie_keys = Movie_List_Constants::$edit_movie_keys;
$non_null_keys = Movie_List_Constants::$non_null_keys;

$movie_keys = $add_movie_keys;

$add_movie_query = "INSERT into movies (title, genre_id, theater_release, dvd_release, pre_rating) values ($1, $2, $3, $4, $5);";
$movie_query = $add_movie_query;


//*******validate data
//**only title is necessary. 
//For everything else, if not set it is okay, but if there's an error, will output error message instead of inserting or updating partly correct data

foreach ($movie_keys as $key) {
	if(!isset($movie[$key]) && !in_array($key, $non_null_keys)){
		$prepared_statement_array[$key] = null;
	}
	else{
		if(!$validator->isValid($key)){
			$errors[] = Movie_List_Constants::error_for_key($key);
		}
		else{
			$prepared_statement_array[$key] = $movie[$key];
		}
	}
}

//catch errors
if(count($errors) > 0){
	$error_message = '';
	foreach ($errors as $error) {
		$error_message = $error_message . $error . ' ';
	}
	echo json_encode(['error' => $error_message]);
	die();
}

//no errors, so we're going to insert/update the database
$db_manager = new AGED_PG_Database_Manager();
$con = $db_manager->get_database_connection_object();
pg_prepare($con, "add_edit_movie_query", $movie_query);
pg_execute($con, "add_edit_movie_query", $prepared_statement_array);
pg_close($con);

//return new table rows
include_once(CONTROLLERS_PATH.'page_controller.php');
$page_controller = new AGED_Index_Controller;
echo json_encode(['table_body' => $page_controller->get_table_content_rows()]);
die();

