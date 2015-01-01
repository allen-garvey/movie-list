<?php 

class AGED_Page_Controller{
	protected $page_name;

	function __construct($page_name){
		$this->page_name = $page_name;
	}

	public function get_title(){
		return "Allen's Movie List";
	}

	function get_nav(){
		$titles = array('Main', 'Suggestions');
		$titles_and_classes = array();
		foreach ($titles as $title) {
			if($title === $this->page_name){
				$titles_and_classes[$title] = "menu_box selected";
			}
			else{
				$titles_and_classes[$title] = "menu_box";	
			}
		}

		return "<nav><div class='$titles_and_classes[Main]'><a href='index.php'>Main</a></div><div class='$titles_and_classes[Suggestions]'><a href='suggestions.php'>Suggestions</a></div></nav>";
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
		$valid_sort_variables_hash = array('Main' => array('title', 'pre_rating desc', 'release_date', 'release'), 'Suggestions' => array('pre_rating desc', 'title', 'genre','release_date','release'));
		$sort_variables_array = explode(',', $sort_variables);
		$check_array = $valid_sort_variables_hash[$this->page_name];

		foreach ($sort_variables_array as $sorter) {
			if(!in_array($sorter, $check_array)){
				return false;
			}
		}
		return true;
	}

}


?>