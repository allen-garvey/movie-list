<?php

class AGED_PG_Database_Manager{
	protected function get_database_connection(){
		return 'localhost';
	}
	protected function get_database_username(){
		return "'Allen X'";
	}
	protected function get_database_password(){
		return '';
	}
	protected function get_database_name(){
		return 'allen_garvey_lists';
	}

	protected function get_database_port(){
		return '5432';
	}

	public function get_database_connection_object(){
		$database_connection = $this->get_database_connection();
		$database_username = $this->get_database_username();
		$database_password = $this->get_database_password();
		$database_name = $this->get_database_name();
		$database_port = $this->get_database_port();

		$con = pg_connect("host=$database_connection port=$database_port dbname=$database_name user=$database_username");

		// Check connection
		if (!$con){
			die("Failed to connect to Postgres");
		}
		return $con;
	}

	public function database_date_format_us($date, $delimiter='/'){
		if($date === null){
			return $date;
		}
		$date = date_parse_from_format('Ymd', $date);
		return $date['month'] . $delimiter . $date['day'] . $delimiter . $date['year'];
	}

	public function get_array_from_result($result){
		$result_array = array();
		while($info = pg_fetch_array($result)){
			$result_array[] = $info;
		}
		return $result_array;
	}



}

?>