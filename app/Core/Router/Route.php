<?php

namespace MA\PHPMVC\Core\Router;

final class Route
{
    private ?string $controller;
    private $action;
    private array $middlewares;
    private $callback;

    public function __construct($callback, $middlewares)
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
            throw new \InvalidArgumentException('Invalid callback provided');
        }
    }

    private function parseControllerAction($callback): array
    {
        $this->validateControllerActionFormat($callback);

        [$class, $method] = $callback;

        $this->validateClassAndMethod($class, $method);

        return $callback;
    }

    private function validateControllerActionFormat($callback): void
    {
        if (!is_array($callback) || count($callback) !== 2) {
            throw new \InvalidArgumentException('Invalid controller action format');
        }
    }

    private function validateClassAndMethod(string $class, string $method): void
    {
        if (!is_string($class) || !class_exists($class)) {
            throw new \InvalidArgumentException('Invalid class name in controller action');
        }

        if (!is_string($method) || !method_exists($class, $method)) {
            throw new \InvalidArgumentException('Invalid method name in controller action');
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