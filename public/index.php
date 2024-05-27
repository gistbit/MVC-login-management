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

// Load the routes configuration
require CONFIG . '/routes.php';

// Initialize the router with Request and Response objects
$router = new Router(new Request(), new Response());

// Run the router
$response = $router->run();

// Send the response to the user
$response->send();
