<?php 
include_once(CONTROLLERS_PATH.'localhost_database_pg.php');
include_once(INC_PATH.'constants.php');


class AGED_Page_Controller_Factory{
	public static function controller_from_page_type(int $page_type=null) : AGED_Page_Controller{
		switch ($page_type) {
			case AGED_Page_Controller::PAGE_SUGGESTIONS:
				return new AGED_Suggestions_Controller;
				break;
			case AGED_Page_Controller::PAGE_RATED:
				return new AGED_Rated_Controller;
				break;
			default:
				return new AGED_Index_Controller;
				break;
		}
	}
}



abstract class AGED_Page_Controller{
	const PAGE_INDEX = 1;
	const PAGE_SUGGESTIONS = 2;
	const PAGE_RATED = 3;

	protected $default_query_sort_args; //used if there are no sorting variables in get
	protected $db_query; //db_query that table results come from (minus any ORDER BY statements or ending semicolon)
	protected $db_manager;
	protected $valid_sort_variables_array; //used to determine if sort variables from get are valid

	public static function get_movie_genre_result(){
		$db_manager = new AGED_PG_Database_Manager;
		$con = $db_manager->get_database_connection_object();
		$movie_genre_result = pg_query($con, 'SELECT genre_id, title FROM m_genre ORDER BY title;') or die(pg_last_error($con)); 
		pg_close($con);
		return $movie_genre_result;
	}

	protected function init_controller(){
		$this->db_manager = new AGED_PG_Database_Manager;
	}

	public function get_title() : string{
		return ucfirst($this->get_name());
	}

	public function get_sort_variables(){
		if(!empty($_GET['sort']) and $this->is_valid_sort_variables($_GET['sort'])){
			return $_GET['sort'];
		}
		else{
			return null;
		}
	}

	protected function is_valid_sort_variables($sort_variables) : bool{
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

	function get_table_content_rows() : string{
		return $this->get_rows_from_result($this->get_query_result($this->get_sort_variables()));
	}

	protected function get_query_result($sort_by=null){
		$sort_by = is_null($sort_by) ? $this->default_query_sort_args : $sort_by;
		$con = $this->db_manager->get_database_connection_object();
		$query_result = pg_query($con, $this->db_query . " ORDER BY $sort_by;") or die(pg_last_error($con)); 
		pg_close($con);

		return $query_result;
	}

	abstract protected function get_rows_from_result($result) : string;

	abstract public function get_page_type() : int;

	public function get_body_tags() : string{
		return 'page_'.$this->get_name();
	}

	abstract public function get_name() : string;	

}


/**
* For movie list 2 index page
*/
class AGED_Index_Controller extends AGED_Page_Controller
{
	
	function __construct(){
		$this->init_controller();
		$this->default_query_sort_args = 'release,release_date,title';
		$this->db_query = "SELECT id, title, pre_rating, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN 'theater_released' ELSE 'unreleased' END END AS RELEASE, CASE WHEN dvd_release <= CURRENT_DATE THEN date '" . Movie_List_Constants::$released_movie_dummy_pg_date."' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '". Movie_List_Constants::$dvd_lead_time_in_days. "' DAY ELSE dvd_release END ELSE theater_release END END AS release_date FROM movies WHERE date_watched IS NULL AND post_rating IS NULL";
		$this->valid_sort_variables_array = array('title', 'pre_rating', 'release_date', 'release');
	}

	protected function get_rows_from_result($unwatched_movies_result) : string{
		$rows = '';

		while($movie = pg_fetch_array($unwatched_movies_result)){
			$date = $this->db_manager->database_date_format_us($movie['release_date']);
			$date = ($date === $this->db_manager->database_date_format_us(Movie_List_Constants::$released_movie_dummy_pg_date)) ? '' : $date;

			$rows = $rows . "<tr class='$movie[release]' data-id='$movie[id]'><td></td><td><a href='" . SUPER_SEARCH_URL  . htmlentities($movie['title']) . "'>".htmlentities($movie['title']) . "</a></td><td>$movie[pre_rating]</td><td>$date</td><td><button class='btn btn-default btn-xs edit-button'>Edit</button></td></tr>";
		}
		return $rows;
	}

	public function get_page_type() : int{
	 	return AGED_Page_Controller::PAGE_INDEX;
	}

	public function get_name() : string{
		return 'home';
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
		$this->default_query_sort_args = 'pre_rating desc,title';
		$this->db_query = "SELECT movies.id, movies.title, movies.pre_rating, m_genre.title as genre, CASE WHEN movies.dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE 'theater_released' END AS RELEASE, CASE WHEN movies.dvd_release <= CURRENT_DATE THEN date '". Movie_List_Constants::$released_movie_dummy_pg_date ."' ELSE CASE WHEN movies.theater_release <= CURRENT_DATE THEN CASE WHEN movies.dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '". Movie_List_Constants::$dvd_lead_time_in_days ."' DAY ELSE movies.dvd_release END ELSE movies.theater_release END END AS release_date FROM movies INNER JOIN m_genre ON movies.genre_id = m_genre.genre_id WHERE movies.date_watched IS NULL AND movies.post_rating IS NULL AND (movies.dvd_release <= CURRENT_DATE or movies.theater_release <= CURRENT_DATE)";
		$this->valid_sort_variables_array = array('pre_rating', 'title', 'genre','release_date','release');
	}

	function get_rows_from_result($released_unwatched_result) : string{
		$rows = '';

		while($movie = pg_fetch_array($released_unwatched_result)){
			$type = $movie['release'];
			$release_date = ($type === 'theater_released') ? $this->db_manager->database_date_format_us($movie['release_date']) : '';
			$rows = $rows . "<tr class='$type' data-id='$movie[id]'><td></td><td><a href='" . SUPER_SEARCH_URL  . htmlentities($movie['title']) . "'>".htmlentities($movie['title']) . "</a></td><td>$movie[pre_rating]</td><td>$release_date</td><td>$movie[genre]</td><td><button class='btn btn-default btn-xs edit-button'>Edit</button></td></tr>";
		}
		return $rows;
	}

	public function get_page_type() : int{
	 	return AGED_Page_Controller::PAGE_SUGGESTIONS;
	}

	public function get_name() : string{
		return 'suggestions';
	}
}

/**
* Controller for movie list 2 - already watched movies page
*/
class AGED_Rated_Controller extends AGED_Page_Controller
{
	public static function get_css_class_for_rating(int $rating) : string{
		if($rating >= Movie_List_Constants::$good_movie_rating){
			return 'good';
		}
		elseif($rating >= Movie_List_Constants::$ok_movie_rating){
			return 'ok';
		}
		return 'bad';
	}

	function __construct()
	{
		$this->init_controller();
		$this->default_query_sort_args = 'post_rating desc,title';
		$this->db_query = "SELECT movies.id, movies.title, m_genre.title as genre, movies.date_watched, movies.pre_rating, movies.post_rating, movies.post_rating, movies.post_rating - movies.pre_rating AS rating_difference FROM movies INNER JOIN m_genre ON movies.genre_id = m_genre.genre_id WHERE movies.post_rating IS NOT NULL";
		$this->valid_sort_variables_array = array('pre_rating', 'post_rating' , 'title', 'genre','date_watched','rating_difference');
	}

	function get_rows_from_result($released_unwatched_result) : string{
		$rows = '';

		while($movie = pg_fetch_array($released_unwatched_result)){
			$date = $this->db_manager->database_date_format_us($movie['date_watched']);
			$class = self::get_css_class_for_rating($movie['post_rating']);

			$rows = $rows . "<tr class='$class' data-id='$movie[id]'><td></td><td><a href='" . SUPER_SEARCH_URL  . htmlentities($movie['title']) . "'>". htmlentities($movie['title'])."</a></td><td>$movie[pre_rating]</td><td>$movie[post_rating]</td><td>$movie[rating_difference]</td><td>$movie[genre]</td><td>$date</td><td><button class='btn btn-default btn-xs edit-button'>Edit</button></td></tr>";
		}
		return $rows;
	}

	public function get_page_type() : int{
	 	return AGED_Page_Controller::PAGE_RATED;
	}

	public function get_name() : string{
		return 'rated';
	}
	
}

