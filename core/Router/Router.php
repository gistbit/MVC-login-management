<?php

namespace MA\PHPMVC\Router;

use Exception;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Interfaces\SendResponse;
use MA\PHPMVC\Router\Route;
use MA\PHPMVC\Router\Running;
use MA\PHPMVC\Utility\Config;

class Router
{
    private static array $routes = [];
    public static Request $request;
    public static Response $response;

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

    private function dispatch(string $method, string $path): ?Route
    {
        foreach (self::$routes[$method] ?? [] as $route) {
            $pattern = '#^' . $route['path'] . '$#';
            if (preg_match($pattern, $path, $variabels)) {
                array_shift($variabels);
                $variabels[] = self::$request;
                return new Route($route['callback'], $route['middlewares'], $variabels);
            }
        }
        return null;
    }

    public function __construct(Request $request, Response $response)
    {
        self::$request = $request;
        self::$response = $response;
    }

    public function run(): SendResponse
    {
        try {
            $route = $this->dispatch($this->getMethod(), $this->getPath());

            if ($route === null) {
                return self::$response->setNotFound('Route not found');
            }

            $middlewares = array_map(fn($middleware) => new $middleware(), $route->getMiddlewares());
            $running = new Running(...$middlewares);

            $running->execute(self::$request, fn() => $this->handleRouteCallback($route));

            return self::$response;
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    private function handleRouteCallback(Route $route)
    {
        if ($route->getController() === null) {
            $content = call_user_func_array($route->getAction(), $route->getParameter());
            self::$response->setContent($content);
        } else {
            $this->runController($route->getController(), $route->getAction(), $route->getParameter());
        }
    }

    private function runController(string $controller, string $method, $parameter)
    {
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {
                $content = call_user_func_array([$controllerInstance, $method], $parameter);
                self::$response->setContent($content);
            } else {
                throw new Exception(sprintf("Method %s not found in %s", $method, $controller));
            }
        } else {
            throw new Exception(sprintf("Controller class %s not found", $controller));
        }
    }

    private function cleanPath($path): string
    {
        return ($path === '/') ? $path : str_replace(['%20', ' '], '-', rtrim($path, '/'));
    }

    private function getPath(): string
    {
        return $this->cleanPath(self::$request->getPath());
    }

    private function getMethod(): string
    {
        return strtoupper(self::$request->getMethod());
    }

    private function responseError($message): Response
    {
        if(Config::isDevelopmentMode()){
            return self::$response->setStatusCode(200)->setContent(view('error/dev', ['message' => $message]));
        }else{
            return self::$response->setStatusCode(500)->setContent(view('error/500'));
        }
    }
}
