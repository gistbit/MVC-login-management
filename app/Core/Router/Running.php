<?php

namespace MA\PHPMVC\Core\Router;

use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Interfaces\Request;

class Running
{
    private array $middlewares;

    public function __construct(Middleware ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function process(Request $request, callable $nextHandler)
    {
        if ($this->middlewares) {
            $this->prosesMiddlewares($request);
        }

        $nextHandler();
    }

    private function prosesMiddlewares(Request $request)
    {
        foreach ($this->middlewares as $middleware) {
            if (!$middleware->process($request)) {
                return;
            }
        }
    }
}
