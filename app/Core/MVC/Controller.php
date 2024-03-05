<?php

namespace MA\PHPMVC\Core\MVC;

use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Interfaces\Response as InterfacesResponse;

abstract class Controller
{
    protected Request $request;
    protected InterfacesResponse $response;

    public function __construct()
    {
        $this->request = request();
        $this->response = response();
    }
    
    protected function renderViewOnly(string $view, array $model = []){
        return View::renderViewOnly($view, $model);
    }

    protected function renderView(string $view, array $model = []){
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
