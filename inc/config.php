<?php 
define('ENVIRONMENT_LOCAL', 0);
define("ENVIRONMENT_CURRENT", ENVIRONMENT_LOCAL);

if(ENVIRONMENT_CURRENT === ENVIRONMENT_LOCAL){
	define("BASE_URL","/movie_list_2/");
	define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"] . "/movie_list_2/");
}

