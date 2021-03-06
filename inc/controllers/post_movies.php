<?php 

if( !isset($_POST['movie']) || is_null(json_decode($_POST['movie'], true)) ) {
	http_response_code(400);
	echo json_encode(['error' => "Bad request: no movie sent"]);
	die();
}

require_once(INC_PATH . 'constants.php');
require_once(CONTROLLERS_PATH.'movie_data_validator.php');
require_once(CONTROLLERS_PATH.'localhost_database_pg.php');

$request_type = 'add';
if(isset($_POST['method']) && $_POST['method'] === 'PATCH'){
	$request_type = 'update';
}

$movie = json_decode($_POST['movie'], true);
$validator = new MovieDataValidator($movie);
$errors = [];
$prepared_statement_array = [];
$non_null_keys = Movie_List_Constants::$non_null_keys;

if($request_type === 'add'){
	$movie_keys = Movie_List_Constants::$add_movie_keys;
	$movie_query = "INSERT into movies (title, genre_id, theater_release, dvd_release, pre_rating) values ($1, $2, $3, $4, $5);";
}
else{
	$movie_keys = Movie_List_Constants::$edit_movie_keys;
	$movie_query = "UPDATE movies set title = $1, genre_id = $2, theater_release = $3, dvd_release = $4, pre_rating = $5, post_rating = $6 where id = $7;";
}



//*******validate data
//**only title is necessary. 
//For everything else, if not set it is okay, but if there's an error, will output error message instead of inserting or updating partly correct data

foreach ($movie_keys as $key) {
	if(($key === 'theater_release' || $key === 'dvd_release') && !empty($movie[$key])){
		$date = DateTime::createFromFormat('Y-m-d', $movie[$key]);
		if(is_object($date)){
			$prepared_statement_array[$key] = $date->format('Y-m-d');
		}
		else{
			$errors[] = 'Date format error ' . $key . ' value is ' . $movie[$key];
		}
	}
	elseif(empty($movie[$key]) && !in_array($key, $non_null_keys)){
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
	$error_message = implode(' ', $errors);
	http_response_code(400);
	echo json_encode(['error' => $error_message]);
	die();
}

//no errors, so we're going to insert/update the database
$db_manager = new AGED_PG_Database_Manager();
$con = $db_manager->get_database_connection_object();
$success = pg_prepare($con, "add_edit_movie_query", $movie_query);
if($success === false){
	pg_close($con);
	http_response_code(500);
	echo json_encode(['error' => 'There was a problem creating the prepared statement']);
	die();
}
$success = pg_execute($con, "add_edit_movie_query", $prepared_statement_array);
if($success === false){
	pg_close($con);
	http_response_code(500);
	echo json_encode(['error' => "There's a problem with the database"]);
	die();
}
pg_close($con);

//return new table rows
include_once(CONTROLLERS_PATH.'page_controller.php');
$page_type = $_POST['page_type'] ?? AGED_Page_Controller::PAGE_INDEX;
$page_controller = AGED_Page_Controller_Factory::controller_from_page_type($page_type);
echo json_encode(['table_body' => $page_controller->get_table_content_rows()]);
die();







