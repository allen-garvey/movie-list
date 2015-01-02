<?php

function get_table_content_rows($sort_by=null){
	return get_rows_from_result(get_query_result($sort_by));
}

function get_query_result($sort_by=null){
	$sort_by = is_null($sort_by) ? 'pre_rating desc,title' : $sort_by;
	$db_manager = new AGED_PG_Database_Manager;
	$con = $db_manager->get_database_connection_object();

	//double sorting is slightly inefficient, but requires less code to fix
	$released_unwatched_result = pg_query($con, "SELECT title, pre_rating, genre, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE 'theater_released' END AS RELEASE, CASE WHEN dvd_release <= CURRENT_DATE THEN date '". Movie_List_Constants::$released_movie_dummy_pg_date ."' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '". Movie_List_Constants::$dvd_lead_time_in_days ."' DAY ELSE dvd_release END ELSE theater_release END END AS release_date FROM \"Movies\" WHERE date_watched IS NULL AND post_rating IS NULL AND (dvd_release <= CURRENT_DATE or theater_release <= CURRENT_DATE) ORDER BY $sort_by, pre_rating desc, title;") or die(pg_last_error($con));

	pg_close($con);

	return $released_unwatched_result;
}

function get_rows_from_result($released_unwatched_result){
	$db_manager = new AGED_PG_Database_Manager;
	$i = 1;
	$rows = '';

	while($movie = pg_fetch_array($released_unwatched_result)){
		$type = $movie['release'];
		$release_date = ($type === 'theater_released') ? $db_manager->database_date_format_us($movie['release_date']) : '';
		$rows = $rows . "<tr class='$type' id='row$i'><td>$i</td><td>$movie[title]</td><td>$movie[pre_rating]</td><td>$release_date</td><td>$movie[genre]</td></tr>";
		$i++;
	}
	return $rows;
}

?>