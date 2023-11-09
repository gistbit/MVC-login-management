<?php

namespace Router;

final class Route {
    
   
    private $method;

    private $pattern; //path

    private $callback; //controller and function

    private $middlewares;

    public function __construct(String $method, String $pattern, $callback, $middlewares = []) {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->callback = $callback;
        $this->middlewares = $middlewares;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPattern() {
        return $this->pattern;
    }

    public function getCallback() {
        return $this->callback;
    }
    public function getMiddlewares() {
        return $this->middlewares;
    }
}