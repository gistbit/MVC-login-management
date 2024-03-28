<?php

namespace MA\PHPMVC;

use Exception;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Interfaces\App;
use MA\PHPMVC\Interfaces\SendResponse;
use MA\PHPMVC\Router\Route;
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
        $this->setCorsHeaders();
    }

    private function setCorsHeaders()
    {
        $allowedOrigin = Config::get('app.url');
        self::$response->setHeader('Access-Control-Allow-Origin: ' . $allowedOrigin);
        self::$response->setHeader("Access-Control-Allow-Methods: GET, POST");
        self::$response->setHeader("Access-Control-Allow-Headers: Content-Type");
    }

    public function run(): SendResponse
    {
        try {
            $route = Router::dispatch($this->getMethod(), $this->getPath());

            if ($route === null) {
                return self::$response->setNotFound('Route tidak ditemukan');
            }

            $middlewares = array_map(fn ($middleware) => new $middleware(), $route->getMiddlewares());
            $running = new Running(...$middlewares);

            $running->process(self::$request, function () use ($route) {
                $this->handleRouteCallback($route);
            });

            return self::$response;
        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    private function handleRouteCallback(Route $route)
    {
        $parameter = $route->getParameter();
        $parameter[] = self::$request;
        if ($route->getController() === null) {
            $content = call_user_func_array($route->getAction(), $parameter);
            self::$response->setContent($content);
        } else {
            $this->runController($route->getController(), $route->getAction(), $parameter);
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
