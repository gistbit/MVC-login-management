<?php

namespace MA\PHPMVC\MVC;

use MA\PHPMVC\Application;
use MA\PHPMVC\Interfaces\Request;
use MA\PHPMVC\Interfaces\Response;
use MA\PHPMVC\Router\Router;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected $template = '';

    public function __construct()
    {
        $this->request = Router::$request;
        $this->response = Router::$response;
    }

    protected function view(string $view, array $data = [])
    {
        return View::render($view, $data, $this->template);
    }

    protected function model(string $modelName)
    {
        $modelClass = "\MA\PHPMVC\Models\\" . $modelName;

        $this->checkModelClass($modelClass);

        return new $modelClass;
    }

    private function checkModelClass(string $modelClass)
    {
        if (!class_exists($modelClass)) {
            throw new \Exception(sprintf('{ %s } this model class not found', $modelClass));
        }
    }
}
