<?php

namespace MA\PHPMVC\Core\Interfaces;
use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Core\Router\Router;

interface App{
    public function __construct(Request $request, Response $response);
    public function run(Router $router);
    public function render();
}