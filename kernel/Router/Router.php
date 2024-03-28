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
        self::$routes[$method][] = [
            'path' => $path,
            'callback' => $callback,
            'middlewares' => $middlewares,
        ];
    }

    public static function dispatch(string $method, string $path): ?Route
    {
        foreach (self::$routes[$method] ?? [] as $routes) {
            $pattern = '#^' . $routes['path'] . '$#';
            if (preg_match($pattern, $path, $variabels)) {
                array_shift($variabels);
                return new Route($routes['callback'], $routes['middlewares'], $variabels);
            }
        }
        return null;
    }
}
