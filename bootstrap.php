<?php

use MA\PHPMVC\Utility\Config;
use MA\PHPMVC\Application;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;

// Path constants
define('ROOT', str_replace('\\', '/', rtrim(__DIR__ , '/')));
define('APP', ROOT . '/app');
define('CONTROLLERS', APP . '/Controllers');
define('MODELS', APP . '/Models');
define('VIEWS', APP . '/views');
define('UPLOAD', ROOT . '/public/upload');
define('CONFIG', ROOT . '/config');
define('PUBLIC_HTML', str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')));

// Base URL constants
define('BASE_URL', Config::get('app.url'));
define('UPLOAD_URL', BASE_URL . '/upload');

require_once CONFIG . '/routes.php';

// Initialize the application with dependency injection
$app = new Application(
    new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER),
    new Response()
);

return $app;
