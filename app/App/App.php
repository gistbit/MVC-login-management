<?php

namespace MA\PHPMVC\App;

use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Core\Router\Router;
use MA\PHPMVC\Core\Router\Stack;

final class App implements AppInterface
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
        $this->setCorsHeaders();
        $this->response->setHeader('Content-Type: text/html; charset=UTF-8');
    }

    private function setCorsHeaders(){
        $this->response->setHeader('Access-Control-Allow-Origin: '.Config::get('app.url'));
        $this->response->setHeader("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->response->setHeader("Access-Control-Allow-Headers: Content-Type");
    }

    public function run(Router $router)
    {
        $route = $router->getRoute($this->method, $this->path);

        if ($route === null) {
            $this->response->setNotFound('Route tidak ada');
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
       $this->renderResponse();
    }

    private function renderResponse(){
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
        $this->response->setNotFound("Method [ $method ] tidak ada");
        // $this->response->redirect('/');
    }

    private function handleControllerNotFound(string $controller)
    {
        $this->response->setNotFound("Controller Class [ $controller ] tidak ada");
        // $this->response->redirect('/');
    }

    private function cleanPath($path) : string
    {
        return ($path === '/') ? $path : str_replace(['%20', ' '], '-', rtrim($path, '/'));
    }
}