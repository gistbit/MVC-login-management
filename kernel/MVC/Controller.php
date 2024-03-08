<?php

namespace MA\PHPMVC\MVC;

use MA\PHPMVC\Interfaces\Request;
use MA\PHPMVC\Interfaces\Response;
use MA\PHPMVC\Kernel;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = Kernel::$request;
        $this->response = Kernel::$response;
    }

    protected function renderViewOnly(string $view, array $model = [])
    {
        return View::renderViewOnly($view, $model);
    }

    protected function renderView(string $view, array $model = [])
    {
        return View::renderView($view, $model);
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
