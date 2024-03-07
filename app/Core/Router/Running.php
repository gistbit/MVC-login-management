<?php

namespace MA\PHPMVC\Core\Router;

use Closure;
use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Interfaces\Request;

class Running
{
    private array $middlewares;

    public function __construct(Middleware ...$middlewares)
    {
        $this->middlewares = array_reverse($middlewares);
    }

    public function process(Request $request, Closure $next)
    {
        if($this->middlewares){
            foreach ($this->middlewares as $middleware) {
                $next = function ($request) use ($middleware, $next) {
                    return $middleware->process($request, $next);
                };
            }
        }

        // Start executing the first middleware
        $next($request);
    }
}
