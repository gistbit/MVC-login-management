<?php
// load config and startup file
require_once dirname(__DIR__) . '/config/constants.php';
require_once APP . '/startup.php';

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Router\RouteMaker;
use App\Core\Router\Router;
use App\Core\Config;

use function App\helper\cetak;

Config::load();
// create objects of request and response classes
$request = new Request();
$response = new Response();

// cetak();
// set common headers
$response->setHeader('Access-Control-Allow-Origin: '.Config::get('app.url'));
$response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
$response->setHeader("Access-Control-Allow-Headers: Content-Type");
$response->setHeader('Content-Type: text/html; charset=UTF-8');

$router = new Router($request, $response, new RouteMaker());

// include routes
require_once CONFIG . '/router.php';

// Router Run Request
$router->run();

// Response Render Content
$response->render();
