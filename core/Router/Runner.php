<?php

namespace MA\PHPMVC\Router;

use MA\PHPMVC\Http\Request;
use InvalidArgumentException;
use MA\PHPMVC\Interfaces\Middleware;

class Runner
{
    protected $index = 0;
    protected array $middlewares = [];

    public function __construct(array $middlewares)
    {
        array_map([$this, 'addMiddleware'], $middlewares);
    }

    private function addMiddleware($middleware)
    {
        if (!is_string($middleware) && !$middleware instanceof Middleware && !$middleware instanceof \Closure && !is_callable($middleware)) {
            throw new InvalidArgumentException('Middleware must be a string, Closure, Callable, or an instance of MiddlewareInterface');
        }

        $class = is_string($middleware) && class_exists($middleware) ? new $middleware : $middleware;
        $this->middlewares[] = $class;
    }

    public function exec(Request $request, \Closure $callback)
    {
        $this->middlewares[] = $callback;
      //  reset($this->middlewares);
      
        return $this->handle($request);
    }

    public function handle(Request $request)
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