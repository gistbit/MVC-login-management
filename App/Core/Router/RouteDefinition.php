<?php

namespace App\Core\Router;

final class RouteDefinition {
    
    public $method;

    public $pattern;

    public $controller; 

    public $action;

    public $middleware;

    public function __construct(String $method, String $pattern, $callback, $options = []) {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->parseCallback($callback);
        $this->parseoptions($options);
    }

    private function parseCallback($callback)
    {
        if (is_string($callback)) {
            [$this->controller, $this->action] = $this->parseControllerAction($callback);
        } elseif (is_callable($callback)) {
            $this->controller = null;
            $this->action = $callback;
        } else {
            throw new \InvalidArgumentException(print('Invalid callback provided'));
        }
    }

    private function parseControllerAction($callback)
    {
        $segments = explode('@', $callback);
        if (count($segments) !== 2) throw new \InvalidArgumentException(print('Invalid controller action format'));

        $segments['0'] = "\App\Controllers\\".$segments['0'];

        return $segments;
    }

    private function parseoptions($options = []){

        if (empty($options)) {
            $this->middleware = null;
        } else if (count($options) == 1) {
            $this->middleware = current($options);
            $this->middleware = new $this->middleware;
        } else if (count($options) == 2) {
            [$this->middleware, $role] = $options;
            if(method_exists($this->middleware, $role)){
                $this->middleware = (new $this->middleware)->$role();
            }else{
                throw new \InvalidArgumentException(print('Invalid Class Method or Middleware format'));
            }
        }
    }

    public function add() : Route
    {
        return new Route($this);
    }
}