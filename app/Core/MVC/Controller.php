<?php

namespace MA\PHPMVC\Core\MVC;

use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use Exception;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    protected function view(){
        return new View();
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
            throw new Exception(sprintf('{ %s } this model class not found', $modelClass));
        }
    }
}
