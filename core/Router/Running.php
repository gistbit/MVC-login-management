<?php

namespace MA\PHPMVC\Router;

use Closure;
use MA\PHPMVC\Interfaces\Middleware;
use MA\PHPMVC\Interfaces\Request;

class Running implements Middleware
{
    private array $middlewares;

    public function __construct(Middleware ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function process(Request $request, Closure $next)
    {
        $middlewareStack = $this->middlewares ? $this->createMiddlewareStack($next) : $next;
        $middlewareStack($request);
    }

    private function createMiddlewareStack(Closure $defaultNext): Closure
    {
        $middlewareStack = $defaultNext;

        foreach (array_reverse($this->middlewares) as $middleware) {
            $middlewareStack = function ($request) use ($middleware, $middlewareStack) {
                return $middleware->process($request, $middlewareStack);
            };
        }

        return $middlewareStack;
    }
}
