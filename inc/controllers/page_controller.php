<?php 
include_once(CONTROLLERS_PATH.'localhost_database_pg.php');
include_once(INC_PATH.'constants.php');

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

	function get_nav_items(){
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

		return "<li class='$selected_class[Main]'><a href='". HOME_URL . "'>Main</a></li><li class='$selected_class[Suggestions]'><a href='" . SUGGESTIONS_URL. "'>Suggestions</a></li><li class='$selected_class[Rated]'><a href='" . RATED_URL. "'>Rated</a></li>";
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

	public function uses_ng(){
		return false;
	}

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
		$this->db_query = "SELECT id, title, pre_rating, CASE WHEN dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN 'theater_released' ELSE 'unreleased' END END AS RELEASE, CASE WHEN dvd_release <= CURRENT_DATE THEN date '" . Movie_List_Constants::$released_movie_dummy_pg_date."' ELSE CASE WHEN theater_release <= CURRENT_DATE THEN CASE WHEN dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '". Movie_List_Constants::$dvd_lead_time_in_days. "' DAY ELSE dvd_release END ELSE theater_release END END AS release_date FROM movies WHERE date_watched IS NULL AND post_rating IS NULL";
		$this->valid_sort_variables_array = array('title', 'pre_rating', 'release_date', 'release');
	}

	protected function get_rows_from_result($unwatched_movies_result){
		$rows = '';
		$i = 1;

		while($movie = pg_fetch_array($unwatched_movies_result)){
			$date = $this->db_manager->database_date_format_us($movie['release_date']);
			$date = ($date === $this->db_manager->database_date_format_us(Movie_List_Constants::$released_movie_dummy_pg_date)) ? '' : $date;

			$rows = $rows . "<tr class='$movie[release]' data-id='$movie[id]'><td>$i</td><td><a href='" . SUPER_SEARCH_URL  ."$movie[title]'>$movie[title]</a></td><td>$movie[pre_rating]</td><td>$date</td><td><button class='btn btn-default btn-xs edit-button' ng-click='edit($movie[id])'>Edit</button></td></tr>";
			$i++;
		}
		return $rows;
	}

	public function uses_ng(){
		return true;
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
		$this->db_query = "SELECT movies.title, movies.pre_rating, m_genre.title as genre, CASE WHEN movies.dvd_release <= CURRENT_DATE THEN 'dvd_released' ELSE 'theater_released' END AS RELEASE, CASE WHEN movies.dvd_release <= CURRENT_DATE THEN date '". Movie_List_Constants::$released_movie_dummy_pg_date ."' ELSE CASE WHEN movies.theater_release <= CURRENT_DATE THEN CASE WHEN movies.dvd_release IS NULL AND theater_release IS NOT NULL THEN theater_release + INTERVAL '". Movie_List_Constants::$dvd_lead_time_in_days ."' DAY ELSE movies.dvd_release END ELSE movies.theater_release END END AS release_date FROM movies INNER JOIN m_genre ON movies.genre_id = m_genre.genre_id WHERE movies.date_watched IS NULL AND movies.post_rating IS NULL AND (movies.dvd_release <= CURRENT_DATE or movies.theater_release <= CURRENT_DATE)";
		$this->valid_sort_variables_array = array('pre_rating', 'title', 'genre','release_date','release');
	}

	function get_rows_from_result($released_unwatched_result){
		$i = 1;
		$rows = '';

		while($movie = pg_fetch_array($released_unwatched_result)){
			$type = $movie['release'];
			$release_date = ($type === 'theater_released') ? $this->db_manager->database_date_format_us($movie['release_date']) : '';
			$rows = $rows . "<tr class='$type' data-id='$movie[id]'><td>$i</td><td><a href='" . SUPER_SEARCH_URL  ."$movie[title]'>$movie[title]</a></td><td>$movie[pre_rating]</td><td>$release_date</td><td>$movie[genre]</td></tr>";
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
		$this->db_query = "SELECT movies.title, m_genre.title as genre, movies.date_watched, movies.pre_rating, movies.post_rating, movies.post_rating, movies.post_rating - movies.pre_rating AS rating_difference FROM movies INNER JOIN m_genre ON movies.genre_id = m_genre.genre_id WHERE movies.post_rating IS NOT NULL";
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
			$rows = $rows . "<tr class='$class' data-id='$movie[id]'><td>$i</td><td><a href='" . SUPER_SEARCH_URL  ."$movie[title]'>$movie[title]</a></td><td>$movie[pre_rating]</td><td>$movie[post_rating]</td><td>$movie[rating_difference]</td><td>$movie[genre]</td><td>$date</td></tr>";
			$i++;
		}
		return $rows;
	}
	
}


?>