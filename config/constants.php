<?php

// Define base URL
define('BASE_URL', "http://www.localhost:8080/");

// Define Path app
define('ROOT', str_replace('\\', '/', rtrim(__DIR__, '/')) . '/../');
define('APP', ROOT . 'app/');
define('CONTROLLERS', APP . 'Controllers/');
define('MODELS', APP . 'Models/');
define('VIEWS', APP . 'views/');
define('UPLOAD', ROOT . 'public/upload/');
define('CONFIG', ROOT . 'config/');
define('PUBLIK', ROOT . 'public/');
define('ROUTER', ROOT . 'router/');

// Define source URLs using the base URL
define('SRC_UPLOAD', BASE_URL . 'upload/');
define('SRC_PUBLIC', BASE_URL);

?>
