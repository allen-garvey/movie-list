<?php 
define('ENVIRONMENT_LOCAL', 0);
define('ENVIRONMENT_CURRENT', ENVIRONMENT_LOCAL);

//Path constants
define('ROOT_PATH', dirname(__FILE__, 2).'/');
define('INC_PATH', ROOT_PATH.'inc/');
define('VIEWS_PATH', INC_PATH.'views/');
define('CONTROLLERS_PATH', INC_PATH.'controllers/');

define('SUBDOMAIN', 'movies');
define('BASE_URL', '/');
require_once(INC_PATH.'multisite-config.php');

//Url Constants
define('SUPER_SEARCH_URL', 'http://search.'.DOMAIN.'/index.php?q=');

define('API_URL', BASE_URL.'api/');
define('HOME_URL', BASE_URL.'');
define('RATED_URL', BASE_URL.'rated/');
define('SUGGESTIONS_URL', BASE_URL.'suggestions/');
define('STYLES_URL', BASE_URL.'styles/');
define('SCRIPTS_URL', BASE_URL.'scripts/');

//Application constants
define('APP_TITLE', 'Movie List');


//load database config
require_once(INC_PATH.'db.php');

