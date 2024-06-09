<?php

namespace MA\PHPMVC\Router;

use App\Domain\User;
use App\Service\SessionService;
use App\Repository\SessionRepository;
use Exception;
use MA\PHPMVC\Database\Database;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Router\Route;
use MA\PHPMVC\Router\Runner;
use MA\PHPMVC\Utility\Config;

class Router
{
    public static Router $router;
    private array $routes = [];
    public Request $request;
    public Response $response;
    public ?User $user;

    public function __construct(Request $request, Response $response)
    {
        self::$router = $this;
        $this->request = $request;
        $this->response = $response;
        $this->currentUser();
    }

    public static function get(string $path, $callback, ...$middlewares): void
    {
        self::$router->add('GET', $path, $callback, $middlewares);
    }

    public static function post(string $path, $callback, ...$middlewares): void
    {
        self::$router->add('POST', $path, $callback, $middlewares);
    }

    public static function put(string $path, $callback, ...$middlewares): void
    {
        self::$router->add('PUT', $path, $callback, $middlewares);
    }

    public static function delete(string $path, $callback, ...$middlewares): void
    {
        self::$router->add('DELETE', $path, $callback, $middlewares);
    }

    private function add(string $method, string $path, $callback, array $middlewares): void
    {
        $this->routes[$method][] = [
            'path' => $path,
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    private function dispatch(string $method, string $path): ?Route
    {
        foreach ($this->routes[$method] ?? [] as $route) {
            $pattern = '#^' . $route['path'] . '$#';
            if (preg_match($pattern, $path, $variabels)) {
                array_shift($variabels);
                $variabels[] = $this->request;
                return new Route($route['callback'], $route['middlewares'], $variabels);
            }
        }
        return null;
    }

    private function currentUser(){
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $sessionService = new SessionService($sessionRepository);
        $this->user = $sessionService->current();
    }

    public function run(): Response
    {
        try {
            $route = $this->dispatch($this->getMethod(), $this->getPath());

            if ($route === null) {
                return $this->response->setNotFound('Route not found');
            }

            $running = new Runner($route->getMiddlewares());

            return $running->exec($this->request, fn() => $this->handleRouteCallback($route));

        } catch (\Throwable $th) {
            return $this->responseError($th->getMessage());
        }
    }

    private function handleRouteCallback(Route $route)
    {
        if ($route->getController() === null) {
            $content = call_user_func_array($route->getAction(), $route->getParameter());
            $this->response->setContent($content);
        } else {
            $this->runController($route->getController(), $route->getAction(), $route->getParameter());
        }
    }

    private function runController(string $controller, string $method, $parameter)
    {
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {
                $content = call_user_func_array([$controllerInstance, $method], $parameter);
                $this->response->setContent($content);
            } else {
                throw new Exception(sprintf("Method %s not found in %s", $method, $controller));
            }
        } else {
            throw new Exception(sprintf("Controller class %s not found", $controller));
        }
    }

    private function cleanPath($path): string
    {
        return ($path === '/') ? $path : str_replace(['%20', ' '], '-', rtrim($path, '/'));
    }

    private function getPath(): string
    {
        return $this->cleanPath($this->request->getPath());
    }

    private function getMethod(): string
    {
        return strtoupper($this->request->getMethod());
    }

    private function responseError($message): Response
    {
        if(Config::isDevelopmentMode()){
            return $this->response->setStatusCode(200)->setContent(view('error/dev', ['message' => $message]));
        }else{
            return $this->response->setStatusCode(500)->setContent(view('error/500'));
        }
    }
}
