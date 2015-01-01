<?php
include_once 'localhost_database.php';

function get_title(){
	return "Allen's Movie List";
}
function get_menu(){
	$menu = "<div id='menu'><div class='menu_box_selected'><a href='http://localhost/movie_list_2/index.php' class='selected'>Main</a></div><div class='menu_box'><a href='http://localhost/movie_list_2/suggestions.php'>Suggestions</a></div></div>";

	return $menu;
}

function get_suggestions_menu(){
	$menu = "<div id='menu'><div class='menu_box'><a href='http://localhost/movie_list_2/index.php'>Main</a></div><div class='menu_box_selected'><a href='http://localhost/movie_list_2/suggestions.php' class='selected'>Suggestions</a></div></div>";

	return $menu;
}

//figure out how to escape quotes in html for function call button onclick
function get_center($result1, $result2, $result3){
	return get_table($result1, $result2, $result3) . "<br><br><br>
<button onclick=\"add_movie()\" class='add_movie'>Add Movie</button>";
}

function get_array_from_result($result){
	$result_array = array();
	$i = 0;
	while($info= pg_fetch_array($result)){
		$result_array[$i] = $info;
		$i++;
	}
	return $result_array;
}


function get_table($result1, $result2, $result3){
	$result_array1 = get_array_from_result($result1);
	$result_array2 = get_array_from_result($result2);
	$result_array3 = get_array_from_result($result3);

	$table = "<table id='movie_table'>";

	$i = 1;
	$table = $table . get_rows($result_array1, 'dvd_released', $i);

	$i = $i + count($result_array1);
	$table = $table . get_rows($result_array2, 'theater_released', $i);

	$i = $i + count($result_array2);
	$table = $table . get_rows($result_array3, 'unreleased', $i);

	$table = $table . '</table>';
	
	return $table;
}

function get_rows($result_array, $type, $i){
	$rows = '';
	$class = "class='$type'";

	foreach ($result_array as $info) {
		$rows = $rows . "<tr $class id='row$i'><td>$i</td><td>" . $info['title'] . '</td><td>' . $info['pre_rating'] .'</td>';

		if($type==='unreleased'){
			$rows = $rows . '<td>' . mysql_date_convert2($info['theater_release']) . '</td>';
		}
		elseif($type==='theater_released'){
			$rows = $rows . '<td>' . mysql_date_convert2($info['dvd_release']) . '</td>';
		}
		else{
			$rows = $rows . '<td>'  . '</td>';
		}
		//figure out how to escape quotes in html for function call
		$rows = $rows . "<td><button onclick=\"edit_movie($i)\" class='edit_button' id='edit_button$i'>Edit</button></td>";

		$rows = $rows . '</tr>';
		$i++;
	}
	return $rows;
}

function mysql_date_convert($mysql_date){
	if($mysql_date === null){
		return $mysql_date;
	}
	$date = explode('-', $mysql_date);
	return $date[1] . '/' . $date[2] . '/' . $date[0];
}

function mysql_date_convert2($mysql_date){
	if($mysql_date === null){
		return $mysql_date;
	}
	$date = date_parse_from_format('Ymd', $mysql_date);
	return $date['month'] . '/' . $date['day'] . '/' . $date['year'];
}

function get_suggestions($all_unwatched_result, $theater_result){
	//$all_unwatched_result and $theater_result names should be flipped but too lazy to do it
	return get_suggestion_table($all_unwatched_result, $theater_result);
}

function get_suggestion_table($all_unwatched_result, $theater_result){
	//$all_unwatched_result and $theater_result names should be flipped but too lazy to do it
	$table = "<table id='movie_table'>";
	
	$table = $table . get_suggestion_rows($all_unwatched_result, $theater_result);

	$table = $table . '</table>';
	
	return $table;
}
function get_suggestion_rows($all_unwatched_result, $theater_result){
	//$all_unwatched_result and $theater_result names should be flipped but too lazy to do it
	$rows = '';
	
	$i = 1;
	$theater_result_array = array();

	$j = 0;

	while($info2= pg_fetch_array($theater_result)){
			$theater_result_array[$j] = $info2;
			$j++;
	}


	while($info= pg_fetch_array($all_unwatched_result)){
		$type = 'dvd_released';
		
		foreach ($theater_result_array as $k) {
			if($info['id'] === $k['id']){
				$type = 'theater_released';
				break;
			}
		}
		
		$class = "class=$type";

		$rows = $rows . "<tr $class id='row$i'><td>$i</td><td>" . $info['title'] . '</td><td>' . $info['pre_rating'] .'</td>';

		if($type==='theater_released'){
			$rows = $rows . '<td>' . mysql_date_convert2($info['dvd_release']) . '</td>';
		}
		else{
			$rows = $rows . '<td>'  . '</td>';
		}

		$rows = $rows . '<td>' . $info['genre'] .'</td>';
		$rows = $rows . '</tr>';
		$i++;
	}
	return $rows;
}

function get_index_center_div(){
	$center = get_menu();
	
	$database_connection = get_database_connection();
	$database_username = get_database_username();
	$database_password = get_database_password();
	$database_name = get_database_name();

	$con = pg_connect("host=$database_connection port=5432 dbname=$database_name user='$database_username'");

	// Check connection
	if (!$con){
		$center =  "Failed to connect to Postgres: ";
	}
	else{
		$data1 = pg_query($con, "SELECT * FROM \"Movies\" where dvd_release <= CURRENT_DATE and date_watched is null and post_rating is null order by title") or die(pg_last_error($con)); 
		
		//improved query so that movies with dvd dates as null are sorted at the bottom
		$dvd_lead_time = dvd_lead_time_in_days();
		//union must have same number of columns so no select *
		$data2 = pg_query($con, "SELECT id, title, theater_release, dvd_release, pre_rating FROM \"Movies\" where theater_release <= CURRENT_DATE and (dvd_release > CURRENT_DATE) and date_watched is null and post_rating is null UNION SELECT id, title, theater_release, (theater_release + INTERVAL '$dvd_lead_time' DAY) as dvd_release, pre_rating FROM \"Movies\" where theater_release <= CURRENT_DATE and (dvd_release is null) and date_watched is null and post_rating is null order by dvd_release, theater_release") or die(pg_last_error($con)); 
		
		$data3 = pg_query($con, "SELECT * FROM \"Movies\" where (theater_release > CURRENT_DATE or theater_release is null) and (dvd_release > CURRENT_DATE or dvd_release is null) and date_watched is null and post_rating is null  order by theater_release") or die(pg_last_error($con)); 

		pg_close($con);
		$center = $center . get_center($data1, $data2, $data3);

	}

	return $center;
}

function add_to_database($title, $pre_rating){
	$database_connection = get_database_connection();
	$database_username = get_database_username();
	$database_password = get_database_password();
	$database_name = get_database_name();

	$con = mysqli_connect($database_connection, $database_username, $database_password, $database_name);

	if (mysqli_connect_errno()){
		$center =  "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else{
		mysqli_query($con, "INSERT into \"Movies\" (title, pre_rating) values ('$title', $pre_rating)") or die(pg_last_error($con)); 
		
		mysqli_close($con);
	}
}

function dvd_lead_time_in_days(){
//used to calculate the dvd_release date of movies with no set dvd_release date for display and sorting purposes
	return 145;
}

function min_rating(){
	return 0;
}

function max_rating(){
	return 100;
}

function sanitized_rating($unsanitized_rating){
	//don't need to test for numeric because php casts this as 0 if the string doesn't contain numeric data
	$rating = (int) $unsanitized_rating;
	
	if ($rating < min_rating()) {
		$rating = min_rating();
	}
	elseif ($rating > max_rating()) {
		$rating = max_rating();
	}
	return $rating;
}


?>