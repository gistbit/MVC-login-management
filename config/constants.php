<?php
define('ORIGIN', "https://localhost:8080");

// Define Path app
define('ROOT', str_replace('\\', '/', rtrim(__DIR__, '/')) . '/../');
define('APP', ROOT . 'app/');
define('CONTROLLERS', ROOT . 'app/Controllers/');
define('MODELS', ROOT . 'app/Models/');
define('VIEWS', ROOT . 'app/views/');
define('UPLOAD', ROOT . 'public/upload/');
define('CONFIG', ROOT . 'config/');
define('ROUTER', ROOT . 'router/');

define('BASE_URL', "https://localhost:8080/");