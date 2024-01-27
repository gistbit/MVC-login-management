<?php

// load config and startup file
require_once __DIR__ . '/../config/constants.php';
require_once APP . 'startup.php';
require_once VENDOR . 'autoload.php';

use App\Core\Router\Router;

use function App\helper\request;
use function App\helper\response;

// create objects of request and response classes
$request = request();
$response = response();

// set common headers
$response->setHeader('Access-Control-Allow-Origin: *');
// $response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
$response->setHeader('Content-Type: text/html; charset=UTF-8');

$router = new Router($request, $response);

// include routes
require_once ROUTER . 'router.php';

// Router Run Request
$router->run();

// Response Render Content
$response->render();
