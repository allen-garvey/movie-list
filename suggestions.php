<?php 
include_once 'localhost_database.php';
include_once 'functions.php';
?>
<!DOCTYPE html>
<html>
	<head><title><?php echo get_title() ?></title>

		<link rel='stylesheet' type='text/css' href='style.css'>


	</head>

<body>
<div id='center'>
	<?php echo get_suggestions_menu(); ?>

	<!-- <h1>What To Watch?</h1> -->
<?php

$con = pg_connect("host=$database_connection port=5432 dbname=$database_name user='$database_username'");

// Check connection
if (!$con){
	echo "Failed to connect to Postgres: " . pg_last_error($con);
}
else{
	$dvd_lead_time = dvd_lead_time_in_days();
	$dvd_result = pg_query($con, "SELECT id, title, theater_release, dvd_release, pre_rating, genre FROM \"Movies\" where (dvd_release <= CURRENT_DATE or  theater_release <= CURRENT_DATE) and date_watched is null and post_rating is null  and dvd_release is not null UNION SELECT id, title, theater_release, (theater_release + INTERVAL '$dvd_lead_time' DAY) as dvd_release, pre_rating, genre FROM \"Movies\" where theater_release <= CURRENT_DATE and (dvd_release is null) and date_watched is null and post_rating is null order by pre_rating desc, title") or die(pg_last_error($con));
	
	$all_unwatched_result = pg_query($con, "SELECT * FROM \"Movies\" where theater_release <= CURRENT_DATE and (dvd_release > CURRENT_DATE or dvd_release is null) and date_watched is null and post_rating is null ") or die(pg_last_error($con)); 

	pg_close($con);
	echo get_suggestions($dvd_result, $all_unwatched_result);
}

?>

</div>


</body></html>