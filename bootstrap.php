<?php

// Path constants
define('ROOT', rtrim(__DIR__, '/'));
define('APP', ROOT . '/app');
define('CONTROLLERS', APP . '/Controllers');
define('MODELS', APP . '/Models');
define('VIEWS', APP . '/views');
define('UPLOAD', ROOT . '/public/upload');
define('CONFIG', ROOT . '/config');
define('DOC_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
define('VENDOR', ROOT . '/vendor');

// Base URL constants
define('BASE_URL', "http://www.localhost:8080");
define('UPLOAD_URL', BASE_URL . '/upload');

use MA\PHPMVC\Application;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;

// Create a request object with superglobal arrays
$request = new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);

// Create a response object
$response = new Response();

// Initialize the application with dependency injection
$app = new Application($request, $response);

return $app;
