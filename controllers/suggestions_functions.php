<?php

function get_suggestion_rows($released_unwatched_result){
	$db_manager = new AGED_PG_Database_Manager;
	$i = 1;
	$rows = '';

	while($movie = pg_fetch_array($released_unwatched_result)){
		$type = $movie['release'];
		$release_date = ($type === 'theater_released') ? $db_manager->database_date_format_us($movie['dvd_release']) : '';
		$rows = $rows . "<tr class='$type' id='row$i'><td>$i</td><td>$movie[title]</td><td>$movie[pre_rating]</td><td>$release_date</td><td>$movie[genre]</td></tr>";
		$i++;
	}
	return $rows;
}

function get_suggestion_table_rows($sort_by=null){
	$sort_by = is_null($sort_by) ? 'pre_rating desc,title' : $sort_by;
	$db_manager = new AGED_PG_Database_Manager;
	$con = $db_manager->get_database_connection_object();
	$dvd_lead_time = Movie_List_Constants::$dvd_lead_time_in_days;
	$null_release_date = Movie_List_Constants::$released_movie_dummy_pg_date;

	//double sorting is slightly inefficient, but requires less code to fix
	$released_unwatched_result = pg_query($con, "SELECT title, pre_rating, theater_release, genre, CASE WHEN dvd_release IS NULL THEN theater_release + INTERVAL '$dvd_lead_time' DAY ELSE dvd_release END AS dvd_release, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE 'theater_released' END AS RELEASE, CASE WHEN dvd_release <= CURRENT_DATE THEN date '$null_release_date' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '$dvd_lead_time' DAY ELSE dvd_release END ELSE theater_release END END AS release_date FROM \"Movies\" WHERE date_watched IS NULL AND post_rating IS NULL AND (dvd_release <= CURRENT_DATE or theater_release <= CURRENT_DATE) ORDER BY $sort_by, pre_rating desc, title;") or die(pg_last_error($con));

	pg_close($con);

	return get_suggestion_rows($released_unwatched_result);
}

?>