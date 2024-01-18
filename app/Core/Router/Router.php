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
    

    public function get($pattern, $callback, $options = [])
    {
        $this->router[] = (new AddRoute('GET', $pattern, $callback, $options))->add();
    }

    public function post($pattern, $callback, $options = [])
    {
        $this->router[] = (new AddRoute('POST', $pattern, $callback, $options))->add();
    }

    public function put($pattern, $callback, $options = [])
    {
        $this->router[] = (new AddRoute('PUT', $pattern, $callback, $options))->add();    
    }

    public function delete($pattern, $callback, $options = [])
    {
        $this->router[] = (new AddRoute('DELETE', $pattern, $callback, $options))->add();
    }


    public function run()
    {
        if (!is_array($this->router) || empty($this->router)) $this->response->setContent('Konfigurasi Ruote Non-Objek');

        $routeMatcher = new RouteMatcher($this->method, $this->url, $this->router);
        $matchRouter = $routeMatcher->getMatchingRoutes();
        // echo '<pre>';
        // print_r($matchRouter);die;
        // echo '</pre>';
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
            call_user_func($action, $params);
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
                $controller->$method();
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
