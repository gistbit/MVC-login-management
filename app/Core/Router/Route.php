<?php

namespace App\Core\Router;

use InvalidArgumentException;

final class Route {
    
    private ?string $controller; 

    private $action;

    private array $middlewares;

    public function __construct($callback, $middlewares) {
        $this->parseCallback($callback);
        $this->middlewares = $middlewares;
    }

    private function parseCallback($callback): void
    {
        if (is_string($callback)) {
            [$this->controller, $this->action] = $this->parseControllerAction($callback);
        } elseif (is_callable($callback)) {
            $this->controller = null;
            $this->action = $callback;
        } else {
            throw new InvalidArgumentException(print('Invalid callback provided'));
        }
    }

    private function parseControllerAction($callback): array
    {
        $segments = explode('@', $callback);
        if (count($segments) !== 2){
            throw new InvalidArgumentException(print('Invalid controller action format'));
        }

        $segments['0'] = "\App\Controllers\\".$segments['0'];

        return $segments;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }
    
    public function getAction()
    {
        return $this->action;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}