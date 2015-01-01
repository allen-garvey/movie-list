<?php
include_once('localhost_database_pg.php');
include_once('models/constants.php');

function get_rows($unwatched_movies_result){
	$db_manager = new AGED_PG_Database_Manager;
	$rows = '';
	$i = 1;

	while($movie = pg_fetch_array($unwatched_movies_result)){
		$type = $movie['release'];
		$date = '';
		if($type==='unreleased'){
			$date = $db_manager->database_date_format_us($movie['theater_release']);
		}
		elseif($type==='theater_released'){
			$date = $db_manager->database_date_format_us($movie['dvd_release']);
		}

		$rows = $rows . "<tr class='$type' id='row$i'><td>$i</td><td>$movie[title]</td><td>$movie[pre_rating]</td><td>$date</td><td><button onclick=\"edit_movie($i)\" class='edit_button' id='edit_button$i'>Edit</button></td></tr>";
		$i++;
	}
	return $rows;
}

function get_table_contents($sort_by='release,title'){
	$db_manager = new AGED_PG_Database_Manager;
	$con = $db_manager->get_database_connection_object();
	$dvd_lead_time = Movie_List_Constants::$dvd_lead_time_in_days;

	$unwatched_movies = pg_query($con, "SELECT title, pre_rating, theater_release, CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '$dvd_lead_time' DAY ELSE dvd_release END AS dvd_release, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN 'theater_released' ELSE 'unreleased' END END AS RELEASE FROM \"Movies\" WHERE date_watched IS NULL AND post_rating IS NULL ORDER BY $sort_by;") or die(pg_last_error($con)); 
	pg_close($con);

	return get_rows($unwatched_movies);
}

?>