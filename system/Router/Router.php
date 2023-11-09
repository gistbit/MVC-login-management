<?php

namespace Router;

class Router {

    private $router = [];

    private $matchRouter = [];

    private $url;

    private $method;

    private $params = [];

    private $response;

    public function __construct(string $url, string $method) {
        $this->url = $this->cleanUrl(rtrim($url, '/'));
        $this->method = $method;
        $this->response = $GLOBALS['response'];
    }

    //callback = controller dan function
    public function get($pattern, $callback, $middlewares = []) {
        $this->addRoute('GET', $pattern, $callback, $middlewares);
    }

    public function post($pattern, $callback, $middlewares = []) {
        $this->addRoute('POST', $pattern, $callback, $middlewares);
    }

    public function put($pattern, $callback, $middlewares = []) {
        $this->addRoute('PUT', $pattern, $callback, $middlewares);
    }

    public function delete($pattern, $callback, $middlewares = []) {
        $this->addRoute('DELETE', $pattern, $callback, $middlewares);
    }

    public function addRoute($method, $pattern, $callback, $middlewares = []) {
        array_push($this->router, new Route($method, $pattern, $callback, $middlewares));
    }

    private function filterRoutesByRequestMethod() {
        foreach ($this->router as $value) {
            if (strtoupper($this->method) == $value->getMethod())
                array_push($this->matchRouter, $value);
        }
    }

    private function filterRoutesByRequestPattern($pattern) {
        $this->matchRouter = [];
        foreach ($pattern as $value) {
            if ($this->dispatch($this->url, $value->getPattern()))
                array_push($this->matchRouter, $value);
        }
    }

    public function dispatch($uri, $pattern) {
        $parsUrl = explode('?', $uri);
        $url = $parsUrl[0];

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

    public function run() {
        if (!is_array($this->router) || empty($this->router)) 
            throw new Exception('Konfigurasi Ruote Non-Objek');

        $this->filterRoutesByRequestMethod();
        $this->filterRoutesByRequestPattern($this->matchRouter);

        if (!$this->matchRouter || empty($this->matchRouter)) {
			$this->sendNotFound();        
		} else {
            $middlewares = $this->matchRouter[0]->getMiddlewares();
            $callback = $this->matchRouter[0]->getCallback();
            
            $this->runMiddleware($middlewares);

            if (is_callable($callback)){
                call_user_func($this->matchRouter[0]->getCallback(), $this->params);
            }else{
                $this->runController($callback, $this->params);
            }
            
        }
    }

    private function runMiddleware($middlewares) {
        foreach ($middlewares as $middleware) {
            $instance = new $middleware;
            $instance->before();
        }
    }
    
    private function runController($controller, $params) {
        $parts = explode('@', $controller);
        $controllerName = $parts[0];
        $methodName = $parts[1] ?? 'index';
    
        // Validasi controller name
        $controllerClass = CONTROLLERS . $controllerName . '.php';
        if (file_exists($controllerClass)) {
            require_once($controllerClass);    
            // Cek apakah class controller yang diharapkan ada
            if (class_exists($controllerName)) {
                $controller = new $controllerName();    
                // Cek apakah method yang diharapkan ada
                if (method_exists($controller, $methodName)) {
                    // Panggil method controller
                    $controller->$methodName($params);
                    // call_user_func([$controller, $methodName], $params);
                    // call_user_func_array([$controller, $methodName], $params);
                    return;
                } else {
                    $this->sendNotFound();
                }
            } else {
                $this->sendNotFound();
            }
        } else {
            $this->sendNotFound();
        }
    }
	
	private function sendNotFound() {
		// $this->response->setContent("Maaf Route tidak ditemukan !");
        $this->response->redirect('/');
	}


    public function cleanUrl($url) {
        return str_replace(['%20', ' '], '-', $url);
    }
}