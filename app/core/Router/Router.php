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
    

    public function get($pattern, $callback, $opstions = [])
    {
        $this->addRoute('GET', $pattern, $callback, $opstions);
    }

    public function post($pattern, $callback, $opstions = [])
    {
        $this->addRoute('POST', $pattern, $callback, $opstions);
    }

    public function put($pattern, $callback, $opstions = [])
    {
        $this->addRoute('PUT', $pattern, $callback, $opstions);
    }

    public function delete($pattern, $callback, $opstions = [])
    {
        $this->addRoute('DELETE', $pattern, $callback, $opstions);
    }

    public function addRoute($method, $pattern, $callback, $opstions = [])
    {
        $this->router[] = (new addRoute($method, $pattern, $callback, $opstions))->add();
    }


    public function run()
    {
        if (!is_array($this->router) || empty($this->router)) $this->response->setContent('Konfigurasi Ruote Non-Objek');

        $routeMatcher = new RouteMatcher($this->method, $this->url, $this->router);
        $matchRouter = $routeMatcher->getMatchingRoutes();
        if ($matchRouter==null) {
            $this->response->setContent("Route tidak ditemukan !");
        } else {
            $params = $routeMatcher->getParams();
            $this->executeRoute($matchRouter, $params);
        }
    }

    private function executeRoute($route, $params=[])
    {
        $middleware = $route->getMiddleware();
        if(!is_null($middleware)) $middleware->before();

        $controller = $route->getController();
        $action = $route->getAction();

        if ($controller == null) {
            call_user_func($action, $params);
        } else {
            $this->runController($controller, $action, $params);
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
                $this->response->setContent("Method tidak ada");
            }
        } else {
            $this->response->setContent("File atau Controller Class tidak ada");
        }
    }

    public function cleanUrl($url)
    {
        return str_replace(['%20', ' '], '-', $url);
    }
}
