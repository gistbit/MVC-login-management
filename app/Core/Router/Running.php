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
        $this->middlewares = $middlewares;
    }

    public function process(Request $request, Closure $finalHandler)
    {
        $next = $finalHandler;

        if($this->middlewares){
            foreach (array_reverse($this->middlewares) as $middleware) {
                $next = function ($request) use ($middleware, $next) {
                    return $middleware->process($request, $next);
                };
            }
        }
        // Mulai eksekusi middleware pertama
        $next($request);
    }
}
