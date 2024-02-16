<?php
// load autoload file
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\App\App;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Router\Router;

$request = new Request();
$response = new Response();
$router = new Router();

require_once CONFIG . '/router.php';

$myApp = new App($request, $response);

$myApp->run($router);