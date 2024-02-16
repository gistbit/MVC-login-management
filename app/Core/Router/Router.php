<?php

namespace App\Core\Router;

use App\Core\Http\{Response, Request};
use App\Core\Router\Stack;

class Router
{
    private string $path;
    private string $method;
    private Request $request;
    private Response $response;
    private RouteMaker $routeMaker;

    public function __construct(Request $request, Response $response, RouteMaker $routeMaker)
    {
        $this->path = $this->cleanUrl(($request->getPath() !== '/') ? rtrim($request->getPath(), '/') : '/');
        $this->method = strtoupper($request->getMethod());
        $this->request = $request;
        $this->response = $response;
        $this->routeMaker = $routeMaker;
    }

    public function get($path, $callback, $middlewares = [])
    {
        $this->routeMaker->make('GET', $path, $callback, $middlewares);
    }

    public function post($path, $callback, $middlewares = [])
    {
        $this->routeMaker->make('POST', $path, $callback, $middlewares);
    }

    public function put($path, $callback, $middlewares = [])
    {
        $this->routeMaker->make('PUT', $path, $callback, $middlewares);
    }

    public function delete($path, $callback, $middlewares = [])
    {
        $this->routeMaker->make('DELETE', $path, $callback, $middlewares);
    }

    public function run()
    {
        $route = $this->routeMaker->getRoute($this->method, $this->path);

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

    private function cleanUrl($url)
    {
        return str_replace(['%20', ' '], '-', $url);
    }
}
