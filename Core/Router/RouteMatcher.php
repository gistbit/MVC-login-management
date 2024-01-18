<?php

namespace App\Core\Router;

class RouteMatcher{
    private string $method;
    private string $url;
    private array $params = [];
    private array $routes;


    public function __construct(string $method, string $url, array $routes){

        $this->method = $method;
        $this->url = $url;
        $this->routes = $routes;
    }

    public function getMatchingRoutes()
    {
        $matchingRoutes = null;
        foreach ($this->routes as $route) {
            if ($this->method == $route->getMethod() && $this->dispatch($this->url, $route->getPattern())) {
                $matchingRoutes = $route;
                return $matchingRoutes;
            }
        }
        return $matchingRoutes;
    }

    public function getParams(){
        return $this->params;
    }

    private function setParams($key, $value) {
        $this->params[$key] = $value;
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

    private function convertPatternToRegex($matches) {
        $key = str_replace(':', '', $matches[0]);
        return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
    }


}