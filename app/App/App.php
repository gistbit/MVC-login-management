<?php

namespace MA\PHPMVC\App;

use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Core\Router\Router;
use MA\PHPMVC\Core\Router\Stack;

final class App
{
    private string $path;
    private string $method;
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->path = $this->cleanPath($request->getPath());
        $this->method = strtoupper($request->getMethod());

        $this->setup();
    }

    private function setup()
    {
        Config::load();
        $this->response->setHeader('Access-Control-Allow-Origin: '.Config::get('app.url'));
        $this->response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->response->setHeader("Access-Control-Allow-Headers: Content-Type");
        $this->response->setHeader('Content-Type: text/html; charset=UTF-8');
    }

    public function run(Router $router)
    {
        $route = $router->getRoute($this->method, $this->path);

        if ($route === null) {
            $this->response->setContent('Route tidak ada');
            return;
        }

        $this->runMiddlewares($route->getMiddlewares(), function() use($route) {
            if ($route->getController() === null) {
                $content = call_user_func($route->getAction(), $this->request);
                $this->response->setContent($content);
            } else {
                $this->runController($route->getController(), $route->getAction());
            }
        });

    }

    private function runMiddlewares($middlewares, callable $next)
    {
        if (empty($middlewares)) {
            $next(); return;
        }

        $stack = new Stack(...array_map(
            fn($middleware) => new $middleware(), $middlewares
        ));

        if($stack->handle($this->request)){
            $next();
        }else if($this->response->getContent() !== null){
            $this->response->setNotFound();
        }
    }

    private function runController(string $controller, string $method)
    {
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {
                $content = $controllerInstance->$method($this->request);
                $this->response->setContent($content);
            } else {
                $this->handleMethodNotFound($method);
            }
        } else {
            $this->handleControllerNotFound($controller);
        }
    }

    public function __destruct()
    {
        if ($this->response->getContent()) {
            http_response_code($this->response->getStatusCode());
            if (!headers_sent()) foreach ($this->response->getHeaders() as $header) {
                header($header);
            }
            echo $this->response->getContent();
        }
    }

    private function handleMethodNotFound(string $method)
    {
        $this->response->setContent("Method [ $method ] tidak ada");
        // $this->response->redirect('/');
    }

    private function handleControllerNotFound(string $controller)
    {
        $this->response->setContent("Controller Class [ $controller ] tidak ada");
        // $this->response->redirect('/');
    }

    private function cleanPath($path) : string
    {
        return ($path === '/') ? $path : str_replace(['%20', ' '], '-', rtrim($path, '/'));
    }
}