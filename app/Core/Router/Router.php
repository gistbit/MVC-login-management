<?php

namespace App\Core\Router;

class Router
{
    private array $routes = [];

    public function get($path, $callback, $middlewares = [])
    {
        $this->add('GET', $path, $callback, $middlewares);
    }

    public function post($path, $callback, $middlewares = [])
    {
        $this->add('POST', $path, $callback, $middlewares);
    }

    public function put($path, $callback, $middlewares = [])
    {
        $this->add('PUT', $path, $callback, $middlewares);
    }

    public function delete($path, $callback, $middlewares = [])
    {
        $this->add('DELETE', $path, $callback, $middlewares);
    }

    private function add(string $method, string $path, $callback, array $middlewares): void
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
