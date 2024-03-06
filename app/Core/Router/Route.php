<?php

namespace MA\PHPMVC\Core\Router;

final class Route
{
    private ?string $controller;
    private $action;
    private array $middlewares;
    private $callback;

    public function __construct($callback, array $middlewares)
    {
        $this->callback = $callback;
        $this->middlewares = $middlewares;
    }

    public function parseCallback(): void
    {
        if (is_array($this->callback)) {
            [$this->controller, $this->action] = $this->parseControllerAction($this->callback);
        } elseif (is_callable($this->callback)) {
            $this->controller = null;
            $this->action = $this->callback;
        } else {
            throw new \InvalidArgumentException("Invalid callback <strong>{ $this->callback }</strong> provided");
        }
    }

    private function parseControllerAction(array $callback): array
    {
        $this->validateControllerActionFormat($callback);

        [$class, $method] = $callback;
        if(!class_exists($class)) return [$method, $class];
        
        return $callback;
    }

    private function validateControllerActionFormat(array $callback): void
    {
        if (count($callback) !== 2) {
            throw new \InvalidArgumentException('Invalid controller action format');
        }
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
