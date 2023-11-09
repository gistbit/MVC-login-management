<?php

// load config and startup file
require __DIR__.'/../config/constanta.php';
require SYSTEM . 'startup.php';
require_once APPLICATION.'init.php';


// create objects of request and response classes
$request = new Http\Request();
$response = new Http\Response();

$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
$response->setHeader('Content-Type: text/html; charset=UTF-8'); 

// set request url and method
$router = new Router\Router($request->getUrl(), $request->getMethod());

require_once ROOT.'Router/Router.php';

// Router Run Request
$router->run();

// Response Render Content
$response->render();
