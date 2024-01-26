<?php

// load config and startup file
require_once __DIR__ . '/../config/constants.php';
require_once APP . 'startup.php';

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Router\Router;

// create objects of request and response classes
$request = new Request();
$response = new Response();

// set common headers
$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
$response->setHeader('Content-Type: text/html; charset=UTF-8');

$router = new Router($request, $response);

// include routes
require_once ROUTER . 'router.php';

// Router Run Request
$router->run();

// Response Render Content
$response->render();
