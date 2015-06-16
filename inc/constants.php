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

	//*****for validation of adding and editing movies
	public static $min_rating = 1;
	public static $max_rating = 100;


	//*******for error messages
	public static $title_error = 'Movies must have a title.';
	public static $rating_error;
	public static $pre_rating_error;
	public static $post_rating_error;

	public static function init(){
		//php can't do non-trivial initialization
		self::$rating_error = 'Please supply a number between ' . self::$min_rating . ' and ' . self::$max_rating;
		self::$pre_rating_error = self::$rating_error . ' for pre-rating.';
		self::$post_rating_error = self::$rating_error . ' for post-rating.';
	}
}

Movie_List_Constants::init();