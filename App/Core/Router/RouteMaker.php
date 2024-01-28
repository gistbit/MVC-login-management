<?php

namespace App\Core\Router;

final class RouteMaker
{
    private array $routes = [];

    public function make($method, $path, $callback, $option)
    {
        if (!isset($this->routes[$method][$path])) {
            $this->routes[$method][$path] = new Route($callback, $option);
        }

        return $this->routes[$method][$path] ;
    }

    public function getRoute($method, $path): ?Route
    {
        return $this->routes[$method][$path]  ?? null;
    }

    public function getAllRoutes(): array
    {
        return $this->routes;
    }

}
