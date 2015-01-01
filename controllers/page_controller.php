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

}


?>