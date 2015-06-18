<?php 

class MovieDataValidator{
	protected $movie;
	
	function __construct(array $movie){
		$this->movie = $movie;	
	}

	public function isTitleValid(){
		return ($this->nullCheck('title') || (is_string($this->movie['title']) && !$this->isEmptyString($this->movie['title'])));
	}
	protected function isEmptyString($string){
		return ($string === '' || preg_match("/^[\s\t\n]+$/",  $string));
	}
	public function isPreRatingValid(){
		return ($this->nullCheck('pre_rating') || $this->isRatingValid($this->movie['pre_rating']));	
	}
	public function isPostRatingValid(){
		return ($this->nullCheck('post_rating') || $this->isRatingValid($this->movie['post_rating']));
	}
	protected function isRatingValid($rating){
		return (is_int($rating) && $rating >= Movie_List_Constants::$min_rating && $rating <= Movie_List_Constants::$max_rating);
	}

	public function isMovieGenreValid(){
		$db_manager = new AGED_PG_Database_Manager();
		return ($this->nullCheck('movie_genre') || in_array($this->movie['movie_genre'], $db_manager->movieGenreIds()));
	}
	public function isTheaterReleaseValid(){
		return ($this->nullCheck('theater_release') || $this->isValidDateFormat($this->movie['theater_release']));
	}
	public function isDvdReleaseValid(){
		return ($this->nullCheck('dvd_release') || $this->isValidDateFormat($this->movie['dvd_release']));
	}
	protected function isValidDateFormat($dateString){
		$date = DateTime::createFromFormat('m/d/Y', $dateString);
		return is_object($date);
	}

	protected function nullCheck($key){
		return (isset($this->movie[$key]) || !in_array($key, Movie_List_Constants::$non_null_keys));
	}

	public function isValid($key){
		switch ($key) {
			case 'title':
				return $this->isTitleValid();
				break;
			case 'pre_rating':
				return $this->isPreRatingValid();
				break;
			case 'post_rating':
				return $this->isPostRatingValid();
				break;
			case 'dvd_release':
				return $this->isDvdReleaseValid();
				break;
			case 'theater_release':
				return $this->isTheaterReleaseValid();
				break;
			case 'movie_genre':
				return $this->isMovieGenreValid();
				break;
			case 'movie_id':
				return true;
				break;
			default:
				return false;
				break;
		}
	}



}
