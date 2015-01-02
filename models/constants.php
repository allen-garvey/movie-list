<?php 
/**
* 
*/
class Movie_List_Constants
{
	public static $dvd_lead_time_in_days = 115;
	public static $released_movie_dummy_pg_date = '0001-01-01';
	//ratings are inculsive ie good >= $good_movie_rating, ok >= $ok_movie_rating, bad < $ok_movie_rating
	public static $good_movie_rating = 85;
	public static $ok_movie_rating = 70;
}

?>