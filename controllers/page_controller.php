<?php 
include_once('localhost_database_pg.php');
include_once('models/constants.php');

abstract class AGED_Page_Controller{
	protected $page_name; //used in nav
	protected $default_query_sort_args; //used if there are no sorting variables in get
	protected $db_query; //db_query that table results come from (minus any ORDER BY statements or ending semicolon)
	protected $db_manager;
	protected $valid_sort_variables_array; //used to determine if sort variables from get are valid

	protected function init_controller(){
		$this->db_manager = new AGED_PG_Database_Manager;
	}

	public function get_title(){
		return "Allen's Movie List";
	}

	function get_nav(){
		$titles = array('Main', 'Suggestions', 'Rated');
		$selected_class = array();
		foreach ($titles as $title) {
			if($title === $this->page_name){
				$selected_class[$title] = "active";
			}
			else{
				$selected_class[$title] = "";	
			}
		}

		return "<header class='jumbotron'><h1 class='main_title'>Movie List</h1><nav><ul class='nav nav-pills'><li class='$selected_class[Main]'><a href='index.php'>Main</a></li><li class='$selected_class[Suggestions]'><a href='suggestions.php'>Suggestions</a></li><li class='$selected_class[Rated]'><a href='rated.php'>Rated</a></li></ul></nav></header>";
	}

	public function get_sort_variables(){
		if(!empty($_GET['sort']) and $this->is_valid_sort_variables($_GET['sort'])){
			return $_GET['sort'];
		}
		else{
			return null;
		}
	}

	protected function is_valid_sort_variables($sort_variables){
		$sort_variables_array = explode(',', $sort_variables);

		foreach ($sort_variables_array as $sorter) {
			$sorter = $this->remove_desc($sorter);
			if(!in_array($sorter, $this->valid_sort_variables_array)){
				return false;
			}
		}
		return true;
	}

	protected function remove_desc($sorter){
		//to replace last desc in sorter
		$search = ' desc';
		$replace = '';

		return preg_replace('~.*\K'. preg_quote($search, '~') . '~si', '$1' . $replace, $sorter);
	}

	function get_table_content_rows(){
		return $this->get_rows_from_result($this->get_query_result($this->get_sort_variables()));
	}

	protected function get_query_result($sort_by=null){
		$sort_by = is_null($sort_by) ? $this->default_query_sort_args : $sort_by;
		$con = $this->db_manager->get_database_connection_object();
		$query_result = pg_query($con, $this->db_query . " ORDER BY $sort_by;") or die(pg_last_error($con)); 
		pg_close($con);

		return $query_result;
	}

	abstract protected function get_rows_from_result($result);

}


/**
* For movie list 2 index page
*/
class AGED_Index_Controller extends AGED_Page_Controller
{
	
	function __construct(){
		$this->init_controller();
		$this->page_name = 'Main';
		$this->default_query_sort_args = 'release,release_date,title';
		$this->db_query = "SELECT title, pre_rating, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN 'theater_released' ELSE 'unreleased' END END AS RELEASE, CASE WHEN dvd_release <= CURRENT_DATE THEN date '" . Movie_List_Constants::$released_movie_dummy_pg_date."' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '". Movie_List_Constants::$dvd_lead_time_in_days. "' DAY ELSE dvd_release END ELSE theater_release END END AS release_date FROM \"Movies\" WHERE date_watched IS NULL AND post_rating IS NULL";
		$this->valid_sort_variables_array = array('title', 'pre_rating', 'release_date', 'release');
	}

	protected function get_rows_from_result($unwatched_movies_result){
		$rows = '';
		$i = 1;

		while($movie = pg_fetch_array($unwatched_movies_result)){
			$date = $this->db_manager->database_date_format_us($movie['release_date']);
			$date = ($date === $this->db_manager->database_date_format_us(Movie_List_Constants::$released_movie_dummy_pg_date)) ? '' : $date;

			$rows = $rows . "<tr class='$movie[release]' id='row$i'><td>$i</td><td>$movie[title]</td><td>$movie[pre_rating]</td><td>$date</td><td><button onclick=\"edit_movie($i)\" class='btn btn-default btn-xs' id='edit_button$i'>Edit</button></td></tr>";
			$i++;
		}
		return $rows;
	}

}

/**
* Controller for movie list 2 - suggestions page
*/
class AGED_Suggestions_Controller extends AGED_Page_Controller
{
	function __construct()
	{
		$this->init_controller();
		$this->page_name = 'Suggestions';
		$this->default_query_sort_args = 'pre_rating desc,title';
		$this->db_query = "SELECT title, pre_rating, genre, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE 'theater_released' END AS RELEASE, CASE WHEN dvd_release <= CURRENT_DATE THEN date '". Movie_List_Constants::$released_movie_dummy_pg_date ."' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '". Movie_List_Constants::$dvd_lead_time_in_days ."' DAY ELSE dvd_release END ELSE theater_release END END AS release_date FROM \"Movies\" WHERE date_watched IS NULL AND post_rating IS NULL AND (dvd_release <= CURRENT_DATE or theater_release <= CURRENT_DATE)";
		$this->valid_sort_variables_array = array('pre_rating', 'title', 'genre','release_date','release');
	}

	function get_rows_from_result($released_unwatched_result){
		$i = 1;
		$rows = '';

		while($movie = pg_fetch_array($released_unwatched_result)){
			$type = $movie['release'];
			$release_date = ($type === 'theater_released') ? $this->db_manager->database_date_format_us($movie['release_date']) : '';
			$rows = $rows . "<tr class='$type' id='row$i'><td>$i</td><td>$movie[title]</td><td>$movie[pre_rating]</td><td>$release_date</td><td>$movie[genre]</td></tr>";
			$i++;
		}
		return $rows;
	}

}

/**
* Controller for movie list 2 - already watched movies page
*/
class AGED_Rated_Controller extends AGED_Page_Controller
{
	function __construct()
	{
		$this->init_controller();
		$this->page_name = 'Rated';
		$this->default_query_sort_args = 'post_rating desc,title';
		$this->db_query = "SELECT title, genre, date_watched, pre_rating, post_rating, post_rating, post_rating - pre_rating AS rating_difference FROM \"Movies\" WHERE post_rating IS NOT NULL";
		$this->valid_sort_variables_array = array('pre_rating', 'post_rating' , 'title', 'genre','date_watched','rating_difference');
	}

	function get_rows_from_result($released_unwatched_result){
		$i = 1;
		$rows = '';

		while($movie = pg_fetch_array($released_unwatched_result)){
			$date = $this->db_manager->database_date_format_us($movie['date_watched']);
			if($movie['post_rating'] >= Movie_List_Constants::$good_movie_rating){
				$class = 'good';
			}
			elseif($movie['post_rating'] >= Movie_List_Constants::$ok_movie_rating){
				$class = 'ok';
			}
			else{
				$class = 'bad';
			}
			$rows = $rows . "<tr class='$class' id='row$i'><td>$i</td><td>$movie[title]</td><td>$movie[pre_rating]</td><td>$movie[post_rating]</td><td>$movie[rating_difference]</td><td>$movie[genre]</td><td>$date</td></tr>";
			$i++;
		}
		return $rows;
	}
	
}


?>