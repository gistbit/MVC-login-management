<?php

namespace MA\PHPMVC\Router;

class Router
{
    private static array $routes = [];

    public static function get(string $path, $callback, ...$middlewares): void
    {
        self::add('GET', $path, $callback, $middlewares);
    }

    public static function post(string $path, $callback, ...$middlewares): void
    {
        self::add('POST', $path, $callback, $middlewares);
    }

    public static function put(string $path, $callback, ...$middlewares): void
    {
        self::add('PUT', $path, $callback, $middlewares);
    }

    public static function delete(string $path, $callback, ...$middlewares): void
    {
        self::add('DELETE', $path, $callback, $middlewares);
    }

    private static function add(string $method, string $path, $callback, array $middlewares): void
    {
        if (!isset(self::$routes[$method][$path])) {
            self::$routes[$method][$path] = new Route($callback, $middlewares);
        }
    }

    public static function getRoute(string $method, string $path, &$matches): ?Route
    {
        foreach (self::$routes[$method] ?? [] as $pattern => $route) {

            if (preg_match("#^$pattern$#", $path, $matches)) {
                array_shift($matches);
                return $route;
            }
        }
        return null;
    }

    public static function getAllRoutes(): array
    {
        return self::$routes;
    }

}