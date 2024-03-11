<?php

namespace MA\PHPMVC\Interfaces;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Router\Router;

interface App{
    public function __construct(Request $request, Response $response);
    public function run(Router $router): SendResponse;
}