<?php

// Include the autoloader to load necessary classes
require_once __DIR__ . '/../vendor/autoload.php';

use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Router\Router;

// Path constants
define('DIR_ROOT', str_replace('\\', '/', dirname(__DIR__)));
define('CONFIG', DIR_ROOT . '/config');
define('VIEWS', DIR_ROOT . '/app/views');
// define('DOC_ROOT', str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')));
// define('UPLOAD', DOC_ROOT . '/upload');

require CONFIG . '/routes.php';

$router = new Router(new Request(), new Response());

// Run the router
$response = $router->run();

// Send the response, which typically involves outputting content to the user
$response->send();
