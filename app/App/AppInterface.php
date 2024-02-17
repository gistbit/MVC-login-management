<?php

namespace MA\PHPMVC\App;

use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Core\Router\Router;

interface AppInterface{
    public function __construct(Request $request, Response $response);
    public function run(Router $router);
    public function __destruct();
}