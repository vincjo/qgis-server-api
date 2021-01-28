<?php
set_time_limit(90); 
require __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use Slim\App;

Dotenv::createImmutable(__DIR__ . '/../')->load();

define('ROOT', dirname(__DIR__) . '/');
define('QGIS_SERVER_URL', getenv('QGIS_SERVER_URL') );
define('API_URL', getenv('API_URL') . '/');
define('PATH_TO_IMAGES', ROOT . "public/images/");
define('PATH_TO_FILES', ROOT . "public/files/");
define('PATH_TO_MAPS', ROOT . "maps/");

$app = new App([
	"settings" => [
		'displayErrorDetails' => true,
		'addContentLengthHeader' => false,
	]
]);