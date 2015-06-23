<?php 
define('ENVIRONMENT_LOCAL', 0);
define("ENVIRONMENT_CURRENT", ENVIRONMENT_LOCAL);

if(ENVIRONMENT_CURRENT === ENVIRONMENT_LOCAL){
	define("BASE_URL","/movie_list_2/");
	define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"] . "/movie_list_2/");
	define("SUPER_SEARCH_URL", 'http://localhost/super-search/index.php?q=');
}

define('HOME_URL', BASE_URL.'index.php');
define('RATED_URL', BASE_URL.'rated.php');
define('SUGGESTIONS_URL', BASE_URL.'suggestions.php');
define('STYLES_URL', BASE_URL.'styles/');
define('SCRIPTS_URL', BASE_URL.'scripts/');

define('INC_PATH', ROOT_PATH.'inc/');
define('VIEWS_PATH', INC_PATH.'views/');
define('CONTROLLERS_PATH', INC_PATH.'controllers/');

