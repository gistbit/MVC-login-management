<?php

namespace App\Core\Router;

final class Route {
    
    private $method;

    private $pattern; 

    private $controller; 

    private $action;

    private $middleware;

    public function __construct(RouteDefinition $route) {
        $this->method = $route->method;
        $this->pattern = $route->pattern;
        $this->controller = $route->controller;
        $this->action = $route->action;
        $this->middleware = $route->middleware;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPattern() {
        return $this->pattern;
    }

    public function getController()
    {
        return $this->controller;
    }
    
    public function getAction()
    {
        return $this->action;
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }
}