<?php

namespace App\Core\Router;

final class RouteMaker
{
    private array $routes = [];

    public function make(string $method, string $path, $callback, array $middlewares): void
    {
        if (!isset($this->routes[$method][$path])) {
            $this->routes[$method][$path] = new Route($callback, $middlewares);
        }
    }

    public function getRoute(string $method, string $path): ?Route
    {
        return $this->routes[$method][$path] ?? null;
    }

    public function getAllRoutes(): array
    {
        return $this->routes;
    }
}
