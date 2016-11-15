<?php 
require_once('../../inc/config.php');

if(!isset($_POST['movie_id'])) {
	http_response_code(400);
	echo json_encode(['error' => "Bad request: no movie_id sent"]);
	die();
}

require_once(CONTROLLERS_PATH.'localhost_database_pg.php');


//Prepare the sql query
$movie_id = $_POST['movie_id'];

if(isset($_POST['active']) && $_POST['active'] === '1' ){
	$updated_active_state = 'TRUE';
}
else{
	$updated_active_state = 'FALSE';
}


$prepared_statement_array = [$movie_id];
//need to manually insert boolean value, since php will convert to string
$update_activation_query = "UPDATE movies set active = $updated_active_state WHERE id = $1";


//execute the query
$db_manager = new AGED_PG_Database_Manager();
$con = $db_manager->get_database_connection_object();
$success = pg_prepare($con, "update_activation_query", $update_activation_query);
if($success === false){
	pg_close($con);
	http_response_code(500);
	echo json_encode(['error' => 'There was a problem creating the prepared statement']);
	die();
}
$success = pg_execute($con, "update_activation_query", $prepared_statement_array);
if($success === false){
	pg_close($con);
	http_response_code(500);
	echo json_encode(['error' => "There was a problem updating the database"]);
	die();
}
pg_close($con);

//return new table rows
include_once(CONTROLLERS_PATH.'page_controller.php');
$page_type = $_POST['page_type'] ?? AGED_Page_Controller::PAGE_INDEX;
$page_controller = AGED_Page_Controller_Factory::controller_from_page_type($page_type);
echo json_encode(['table_body' => $page_controller->get_table_content_rows()]);
die();
