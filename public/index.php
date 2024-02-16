<?php
// load autoload file
require_once dirname(__DIR__) . '/vendor/autoload.php';

use MA\PHPMVC\App\App;
use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Core\Router\Router;

$request = new Request();
$response = new Response();
$router = new Router();

require_once CONFIG . '/router.php';

$kernel = new App($request, $response);

$kernel->run($router);