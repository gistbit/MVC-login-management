<?php

namespace MA\PHPMVC\Router;

class Router
{
    private static array $routes = [];

    public function get(string $path, $callback, ...$middlewares): void
    {
        $this->add('GET', $path, $callback, $middlewares);
    }

    public function post(string $path, $callback, ...$middlewares): void
    {
        $this->add('POST', $path, $callback, $middlewares);
    }

    public function put(string $path, $callback, ...$middlewares): void
    {
        $this->add('PUT', $path, $callback, $middlewares);
    }

    public function delete(string $path, $callback, ...$middlewares): void
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
