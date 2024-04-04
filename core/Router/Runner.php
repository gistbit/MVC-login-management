<?php

namespace MA\PHPMVC\Router;

use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Interfaces\Middleware as MiddlewareInterface;

class Runner
{
    private $index = 0;
    private array $middlewares = [];

    public function __construct(array $middlewares)
    {
        array_map([$this, 'addMiddleware'], $middlewares);
    }

    private function addMiddleware($middleware): void
    {
        if (!($middleware instanceof MiddlewareInterface || is_callable($middleware))) {
            throw new \InvalidArgumentException('Middleware must be an instance of MiddlewareInterface or a callable.');
        }

        $this->middlewares[] = new $middleware;
    }

    public function exec(Request $request, \Closure $callback)
    {
        $this->middlewares[] = $callback;
        return $this->handle($request);
    }

    private function handle(Request $request)
    {
        if (!isset($middleware = $this->middlewares[$this->index])) {
            return Router::$response;
        }

        $result = $this->executeMiddleware($middleware, $request);

        if (is_scalar($result)) {
            return Router::$response->setContent($result);
        } else {
            return Router::$response;
        }
    }

    public function __invoke(Request $request)
    {
        return $this->handle($request);
    }

    private function executeMiddleware($middleware, Request $request)
    {
        if (is_object($middleware) && method_exists($middleware, 'execute')) {
            return $middleware->execute($request, $this->next());
        } elseif (is_callable($middleware)) {
            return $middleware();
        }
    }

    private function next()
    {
        $this->index++;
        return $this;
    }
}