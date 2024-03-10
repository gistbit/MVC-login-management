<?php

namespace MA\PHPMVC;

use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Interfaces\App;
use MA\PHPMVC\Interfaces\RenderResponse;
use MA\PHPMVC\Router\Router;
use MA\PHPMVC\Router\Running;
use MA\PHPMVC\Utility\Config;

final class Application implements App
{
    public static Request $request;
    public static Response $response;

    public function __construct(Request $request, Response $response)
    {
        self::$request = $request;
        self::$response = $response;
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
        $allowedOrigin = Config::get('app.url');
        self::$response->setHeader('Access-Control-Allow-Origin: ' . $allowedOrigin);
        self::$response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        self::$response->setHeader("Access-Control-Allow-Headers: Content-Type");
    }

    public function run(Router $router): RenderResponse
    {
        require_once CONFIG . '/routes.php';
        $route = $router->getRoute($this->getMethod(), $this->getPath());

        if ($route === null) {
            return self::$response->setNotFound('Route tidak ditemukan');
        }

        try {
            $middlewares = array_map(fn ($middleware) => new $middleware(), $route->getMiddlewares());
            $running = new Running(...$middlewares);

            $running->process(self::$request, function () use ($route) {
                $route->parseCallback();
                $this->handleRouteCallback($route);
            });

            return self::$response;
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    private function handleRouteCallback($route)
    {
        if ($route->getController() === null) {
            $content = call_user_func($route->getAction(), self::$request);
            self::$response->setContent($content);
        } else {
            $this->runController($route->getController(), $route->getAction());
        }
    }

    private function runController(string $controller, string $method)
    {
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            $this->invokeControllerMethod($controllerInstance, $method);
        } else {
            self::$response->setNotFound("Controller Class { <strong> $controller </strong> } tidak ada");
        }
    }

    private function invokeControllerMethod($controllerInstance, $method)
    {
        if (method_exists($controllerInstance, $method)) {
            $parameters = (new \ReflectionMethod($controllerInstance, $method))->getParameters();
            $content = empty($parameters) ? $controllerInstance->$method() : $controllerInstance->$method(self::$request);
            self::$response->setContent($content);
        } else {
            self::$response->setNotFound("Method { <strong> $method </strong> } tidak ada");
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

    public function responseError($message): Response
    {
        if(Config::get('mode.development')){
            return self::$response->setStatusCode(200)->setContent(view('error/development', ['message' => $message]));
        }else{
            return self::$response->setStatusCode(500)->setContent(view('error/500'));
        }
    }
}
