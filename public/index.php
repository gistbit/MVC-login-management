<?php

// Load the autoload file
require_once dirname(__DIR__) . '/vendor/autoload.php';

use MA\PHPMVC\Core\App;
use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Core\Router\Router;

// Create instances
$request = new Request();
$response = new Response();
$router = new Router();

// Load routes configuration
require_once CONFIG . '/router.php';

// Initialize the application
$app = new App($request, $response);

// Run the application with the provided router
$app->run($router);
