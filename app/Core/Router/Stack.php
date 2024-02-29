<?php

namespace MA\PHPMVC\Core\Router;

use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Http\Request;

class Stack
{
    private array $middlewares;

    public function __construct(Middleware ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function handle(Request $request): bool
    {
        $result = true;
    
        foreach ($this->middlewares as $middleware) {
            $result = $middleware->process($request);
            if (!$result) break;
        }

        return $result;
    }
}