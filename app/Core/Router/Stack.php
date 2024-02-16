<?php

namespace App\Core\Router;

use App\{Core\Http\Request, Middlewares\Middleware};

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