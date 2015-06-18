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
	public static $genre_error = 'Please make a valid selection for movie genre.';
	public static $rating_error;
	public static $pre_rating_error;
	public static $post_rating_error;
	public static $movie_id_error = "You haven't supplied a valid movie id.";
	

	public static $date_error = 'Please enter a valid date';
	public static $theater_release_error;
	public static $dvd_release_error;

	//***for validation
	public static $add_movie_keys;
	public static $edit_movie_keys;
	public static $non_null_keys;

	public static function init(){
		//php can't do non-trivial initialization
		self::$rating_error = 'Please supply a number between ' . self::$min_rating . ' and ' . self::$max_rating;
		self::$pre_rating_error = self::$rating_error . ' for pre-rating.';
		self::$post_rating_error = self::$rating_error . ' for post-rating.';
		self::$dvd_release_error = self::$date_error . ' for DVD release date.';
		self::$theater_release_error = self::$date_error . ' for theater release date.';

		self::$add_movie_keys = ['title', 'movie_genre', 'theater_release', 'dvd_release', 'pre_rating'];
		self::$edit_movie_keys = ['title', 'movie_genre', 'theater_release', 'dvd_release', 'pre_rating', 'post_rating', 'movie_id'];
		self::$non_null_keys = ['title', 'movie_id'];
	}

	//returns correct error for movie data key
	public static function error_for_key($movie_key){
		switch ($movie_key) {
			case 'title':
				return self::$title_error;
				break;
			case 'pre_rating':
				return self::$pre_rating_error;
				break;
			case 'post_rating':
				return self::$post_rating_error;
				break;
			case 'dvd_release':
				return self::$dvd_release_error;
				break;
			case 'theater_release':
				return self::$theater_release_error;
				break;
			case 'movie_genre':
				return self::$genre_error;
				break;
			case 'movie_id':
				return self::$movie_id_error;
				break;
			default:
				return "An undetermined error (you have entered an invalid key to get the error for).";
				break;
		}
	}
}

Movie_List_Constants::init();