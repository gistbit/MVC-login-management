<?php

namespace MA\PHPMVC\Core;

use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Core\Interfaces\App as InterfacesApp;
use MA\PHPMVC\Core\Interfaces\RenderResponse;
use MA\PHPMVC\Core\Router\Router;
use MA\PHPMVC\Core\Router\Stack;
use MA\PHPMVC\Core\Utility\Config;

final class App implements InterfacesApp
{
    private string $path;
    private string $method;
    public static Request $request;
    public static Response $response;

    public function __construct(Request $request, Response $response)
    {
        self::$request = $request;
        self::$response = $response;
        $this->path = $this->cleanPath(self::$request->getPath());
        $this->method = strtoupper(self::$request->getMethod());
        $this->setup();
    }

    private function setup()
    {
        Config::load();
        $this->setCorsHeaders();
        // self::$response->setHeader('Content-Type: text/html; charset=UTF-8');
    }

    private function setCorsHeaders()
    {
        self::$response->setHeader('Access-Control-Allow-Origin: ' . Config::get('app.url'));
        self::$response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        self::$response->setHeader("Access-Control-Allow-Headers: Content-Type");
    }

    public function run(Router $router): RenderResponse
    {
        require_once CONFIG . '/routes.php';
        $route = $router->getRoute($this->method, $this->path);
        
        if ($route === null) {
            self::$response->setNotFound('Route tidak ditemukan');
            return self::$response;
        }

        $route->parseCallback();
        $this->runMiddlewares(
            $route->getMiddlewares(),
            function () use ($route) {
                if ($route->getController() === null) {
                    $content = call_user_func($route->getAction(), self::$request);
                    self::$response->setContent($content);
                } else {
                    $this->runController($route->getController(), $route->getAction());
                }
            }
        );

        return self::$response;
    }

    private function runMiddlewares($middlewares, callable $next)
    {
        if (empty($middlewares)) {
            $next();
            return;
        }

        $stack = new Stack(...array_map(
            fn ($middleware) => new $middleware(),
            $middlewares
        ));

        if ($stack->handle(self::$request)) {
            $next();
        }
    }

    private function runController(string $controller, string $method)
    {
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {          
                $parameters = (new \ReflectionMethod($controllerInstance, $method))->getParameters();
                $content = empty($parameters) ? $controllerInstance->$method() : $controllerInstance->$method(self::$request);
                self::$response->setContent($content);
            } else {
                self::$response->setNotFound("Method [ $method ] tidak ada");
            }
        } else {
            self::$response->setNotFound("Controller Class [ $controller ] tidak ada");
        }
    }

    private function cleanPath($path): string
    {
        return ($path === '/') ? $path : str_replace(['%20', ' '], '-', rtrim($path, '/'));
    }
}
