<?php

namespace App\Core\Router;

use App\Core\Http\{Response, Request};

class Router
{
    private array $router = [];
    private string $url;
    private string $method;

    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->url = $this->cleanUrl(rtrim($request->getPath(), '/'));
        $this->method = strtoupper($request->getMethod());
        $this->request = $request;
        $this->response = $response;
    }
    

    public function get($pattern, $callback, $options = [])
    {
        $this->addRoute('GET', $pattern, $callback, $options);
    }

    public function post($pattern, $callback, $options = [])
    {
        $this->addRoute('POST', $pattern, $callback, $options);
    }

    public function put($pattern, $callback, $options = [])
    {
        $this->addRoute('PUT', $pattern, $callback, $options);
    }

    public function delete($pattern, $callback, $options = [])
    {
        $this->addRoute('DELETE', $pattern, $callback, $options);
    }

    private function addRoute($method, $pattern, $callback, $options)
    {
        $this->router[] = (new RouteDefinition($method, $pattern, $callback, $options))->add();
    }


    public function run()
    {
        if (!is_array($this->router) || empty($this->router)) $this->response->setContent('Konfigurasi Ruote Non-Objek');

        $routeMatcher = new RouteMatcher($this->method, $this->url, $this->router);
        $matchRouter = $routeMatcher->getMatchingRoutes();
        
        if ($matchRouter==null) {
            // $this->response->setContent("Route tidak ditemukan !");
            $this->response->redirect('/');
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
            $content = call_user_func($action, $params);
            $this->response->setContent($content);
        } else {
            $this->runController($controller, $action);
        }
    }

    private function runController($controller, $method)
    {
        $controllerFile = ROOT . str_replace('\\', '/', $controller) . '.php';    
        if (file_exists($controllerFile) && class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $method)) {
                $content = $controller->$method($this->request);
                $this->response->setContent($content);
            } else {
                // $this->response->setContent("Method tidak ada");
                $this->response->redirect('/');
            }
        } else {
            // $this->response->setContent("File atau Controller Class tidak ada");
            $this->response->redirect('/');
        }
    }

    public function cleanUrl($url)
    {
        return str_replace(['%20', ' '], '-', $url);
    }
}
