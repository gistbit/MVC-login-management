<?php

namespace MA\PHPMVC\Core\Router;

use MA\PHPMVC\Core\Interfaces\GetRoute;
use MA\PHPMVC\Core\Interfaces\Routes;

class Router implements Routes, GetRoute
{
    private static array $routes = [];

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
        if (!isset(self::$routes[$method][$path])) {
            self::$routes[$method][$path] = new Route($callback, $middlewares);
        }
    }

    public function getRoute(string $method, string $path): ?Route
    {
        return self::$routes[$method][$path] ?? null;
    }

    public function getAllRoutes(): array
    {
        return self::$routes;
    }

}
