<?php

namespace MA\PHPMVC\Router;

final class Route
{
    private ?string $controller;
    private $action;
    private array $middlewares;
    private array $parameter;

    public function __construct($callback, array $middlewares, $parameter)
    {
        $this->middlewares = $middlewares;
        $this->parameter = $parameter;
        $this->parseCallback($callback);
    }

    private function parseCallback($callback): void
    {
        if (is_array($callback)) {
            $this->parseControllerAction($callback);
        } elseif (is_callable($callback)) {
            $this->controller = null;
            $this->action = $callback;
        } else {
            throw new \InvalidArgumentException("Invalid callback '{$callback}' provided");
        }
    }

    private function parseControllerAction(array $callback): void
    {
        $this->validateControllerActionFormat($callback);

        [$class, $method] = $callback;
        if (!class_exists($class)) {
            $this->controller = $method;
            $this->action = $class;
        } else {
            $this->controller = $class;
            $this->action = $method;
        }
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

    public function getParameter(): array
    {
        return $this->parameter;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
