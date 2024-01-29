<?php

namespace App\Core\Router;

use App\Core\Http\{Response, Request};

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
    
    public function get($path, $callback, $options = [])
    {
        $this->routeMaker->make('GET', $path, $callback, $options);
    }
    
    public function post($path, $callback, $options = [])
    {
        $this->routeMaker->make('POST', $path, $callback, $options);
    }
    
    public function put($path, $callback, $options = [])
    {
        $this->routeMaker->make('PUT', $path, $callback, $options);
    }
    
    public function delete($path, $callback, $options = [])
    {
        $this->routeMaker->make('DELETE', $path, $callback, $options);
    }
    
    public function run()
    {
        if ($this->routeMaker->getRoute($this->method, $this->path) === null){
            $this->response->setContent('Route tidak ada');
            return;
        }

        $route = $this->routeMaker->getRoute($this->method, $this->path);
        $middleware = $route->getMiddleware();
        if(!is_null($middleware)) $middleware->before($route->getAuth());

        if ($route->getController() == null) {
            $content = call_user_func($route->getAction(), $this->request);
            $this->response->setContent($content);
        } else {
            $this->runController($route->getController(), $route->getAction());
        }
    }

    private function runController($controller, $method)
    { 
        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $method)) {
                $content = $controller->$method($this->request);
                $this->response->setContent($content);
            } else {
                $this->response->setContent("Method tidak ada");
                // $this->response->redirect('/');
            }
        } else {
            $this->response->setContent("File atau Controller Class tidak ada");
            // $this->response->redirect('/');
        }
    }

    public function cleanUrl($url)
    {
        return str_replace(['%20', ' '], '-', $url);
    }
}
