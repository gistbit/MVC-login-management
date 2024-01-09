<?php

namespace App\Core\Router;

use App\Core\Http\Response;
use Exception;

class Router
{
    private array $router = [];
    private string $url;
    private string $method;

    private Response $response;

    public function __construct(string $url, string $method, Response $response)
    {
        $this->url = $this->cleanUrl(rtrim($url, '/'));
        $this->method = strtoupper($method);
        $this->response = $response;
    }
    

    public function get($pattern, $callback, $middlewares = [])
    {
        $this->addRoute('GET', $pattern, $callback, $middlewares);
    }

    public function post($pattern, $callback, $middlewares = [])
    {
        $this->addRoute('POST', $pattern, $callback, $middlewares);
    }

    public function put($pattern, $callback, $middlewares = [])
    {
        $this->addRoute('PUT', $pattern, $callback, $middlewares);
    }

    public function delete($pattern, $callback, $middlewares = [])
    {
        $this->addRoute('DELETE', $pattern, $callback, $middlewares);
    }

    public function addRoute($method, $pattern, $callback, $middlewares = [])
    {
        $this->router[] = new Route($method, $pattern, $callback, $middlewares);
    }


    public function run()
    {
        if (!is_array($this->router) || empty($this->router)) {
            throw new Exception('Konfigurasi Ruote Non-Objek');
        }

        $routeMatcher = new RouteMatcher($this->method, $this->url, $this->router);
        $matchRouter = $routeMatcher->getMatchingRoutes();
        $params = $routeMatcher->getParams();

        if ($matchRouter==null) {
            $this->response->setContent("Maaf Route tidak ditemukan !");
        } else {
            $this->executeRoute($matchRouter, $params);
        }
    }

    private function executeRoute($route, $params=[])
    {
        $middlewares = $route->getMiddlewares();
        $controller = $route->getController();
        $action = $route->getAction();

        $this->runMiddleware($middlewares);

        if ($controller == null) {
            call_user_func($action, $params);
        } else {
            $this->runController($controller, $action, $params);
        }
    }

    private function runMiddleware($middlewares) {
        foreach ($middlewares as $middleware) {
            $instance = new $middleware;
            $instance->before();
        }
    }

    private function runController($controller, $method, $params)
    {
        $controllerFile = ROOT . str_replace('\\', '/', $controller) . '.php';    
        if (file_exists($controllerFile) && class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $method)) {
                $controller->$method($params);
            } else {
                $this->response->setContent("Maaf method tidak ada");
            }
        } else {
            $this->response->setContent("Maaf File atau Controller Class tidak ada");
        }
    }

    public function cleanUrl($url)
    {
        return str_replace(['%20', ' '], '-', $url);
    }
}
