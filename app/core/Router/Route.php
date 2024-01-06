<?php

namespace App\Core\Router;

final class Route {
    
    private $method;

    private $pattern; //path

    private $controller; // controller

    private $action; // function

    private $middlewares;

    public function __construct(String $method, String $pattern, $callback, $middlewares = []) {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->parseCallback($callback);
        $this->middlewares = $middlewares;
    }

    private function parseCallback($callback)
    {
        if (is_string($callback)) {
            list($this->controller, $this->action) = $this->parseControllerAction($callback);
        } elseif (is_callable($callback)) {
            $this->controller = null;
            $this->action = $callback;
        } else {
            throw new \InvalidArgumentException('Invalid callback provided');
        }
    }

    private function parseControllerAction($callback)
    {
        $segments = explode('@', $callback);
        if (count($segments) !== 2) {
            throw new \InvalidArgumentException('Invalid controller action format');
        }

        $segments['0'] = "\App\Controllers\\".$segments['0'];

        return $segments;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPattern() {
        return $this->pattern;
    }

    public function getMiddlewares() {
        return $this->middlewares;
    }



    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }
}