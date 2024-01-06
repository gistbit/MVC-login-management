<?php

namespace App\Core\Router;

use App\Core\Http\Response;
use Exception;

class Router
{
    private array $router = [];
    private array $matchRouter = [];
    private string $url;
    private string $method;
    private array $params = [];

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

    private function filterRoutesByRequestMethod() {
        foreach ($this->router as $value) {
            if (strtoupper($this->method) == $value->getMethod()) {
                $this->matchRouter[] = $value;
            }
        }
    }
    
    private function filterRoutesByRequestPattern($pattern) {
        $this->matchRouter = [];
        foreach ($pattern as $value) {
            if ($this->dispatch($this->url, $value->getPattern())) {
                $this->matchRouter[] = $value;
            }
        }
    }
    private function dispatch($url, $pattern) {
        preg_match_all('@:([\w]+)@', $pattern, $params, PREG_PATTERN_ORDER);
        $patternAsRegex = preg_replace_callback('@:([\w]+)@', [$this, 'convertPatternToRegex'], $pattern);
        if (substr($pattern, -1) === '/' ) {
	        $patternAsRegex = $patternAsRegex . '?';
	    }
        
        $patternAsRegex = '@^' . $patternAsRegex . '$@';
        
        // check match request url
        if (preg_match($patternAsRegex, $url, $paramsValue)) {
            array_shift($paramsValue);
            foreach ($params[0] as $key => $value) {
                $val = substr($value, 1);
                if ($paramsValue[$val]) {
                    $this->setParams($val, urlencode($paramsValue[$val]));
                }
            }

            return true;
        }

        return false;
    }

    private function setParams($key, $value) {
        $this->params[$key] = $value;
    }


    private function convertPatternToRegex($matches) {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }

    public function run()
    {
        if (!is_array($this->router) || empty($this->router)) {
            throw new Exception('Konfigurasi Ruote Non-Objek');
        }

        $this->filterRoutesByRequestMethod();
        $this->filterRoutesByRequestPattern($this->matchRouter);
        // print_r($this->matchRouter);die;

        if (empty($this->matchRouter)) {
            $this->sendNotFound();
        } else {
            $middlewares =  $this->matchRouter[0]->getMiddlewares();
            $controller = $this->matchRouter[0]->getController();
            $action = $this->matchRouter[0]->getAction();
            
            $this->runMiddleware($middlewares);
            if ($controller==null) {
                call_user_func($action, $this->params);
            } else {
                $this->runController($controller, $action, $this->params);
            }
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

    private function sendNotFound()
    {
        $this->response->setContent("Maaf Route tidak ditemukan !");
    }

    public function cleanUrl($url)
    {
        return str_replace(['%20', ' '], '-', $url);
    }
}
