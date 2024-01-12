<?php

namespace App\Core\Router;

final class AddRoute {
    
    public $method;

    public $pattern;

    public $controller; 

    public $action;

    public $middleware;

    public function __construct(String $method, String $pattern, $callback, $options = []) {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->parseCallback($callback);
        $this->parseOption($options);
    }

    private function parseCallback($callback)
    {
        if (is_string($callback)) {
            list($this->controller, $this->action) = $this->parseControllerAction($callback);
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

    private function parseOption($options = []){

        if (empty($option)) {
            $this->middleware = null;
        } else if (count($option) == 1) {
            $this->middleware = current($option);
            $this->middleware = new $this->middleware;
        } else if (count($option) == 2) {
            [$this->middleware, $role] = $option;
            if(method_exists($this->middleware, $role)){
                $this->middleware = (new $this->middleware)->$role();
            }else{
                throw new \InvalidArgumentException(print('Invalid Class Method or Middleware format'));
            }
        }
    }

    public function add()
    {
        return new Route($this);
    }
}