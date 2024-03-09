<?php

namespace MA\PHPMVC\MVC;

use MA\PHPMVC\Interfaces\Request;
use MA\PHPMVC\Interfaces\Response;
use MA\PHPMVC\Kernel;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected $template = '';

    public function __construct()
    {
        $this->request = Kernel::$request;
        $this->response = Kernel::$response;
    }

    protected function view(string $view, array $model = [])
    {
        return View::render($view, $model, $this->template);
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
