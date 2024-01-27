<?php

namespace App\Core\MVC;

use App\Core\Http\Request;
use App\Core\Http\Response;

use function App\helper\request;
use function App\helper\response;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
        $this->request = request();
        $this->response = response();
    }

    public function model(string $modelName)
    {
        $modelFilePath = MODELS . $modelName . ".php";

        $this->checkModelFile($modelFilePath);

        $modelClass = "\App\Models\\" . $modelName;

        $this->checkModelClass($modelClass);

        return new $modelClass;
    }

    private function checkModelFile(string $modelFilePath)
    {
        if (!file_exists($modelFilePath)) {
            throw new \Exception(sprintf('{ %s } this model file not found', $modelFilePath));
        }
    }

    private function checkModelClass(string $modelClass)
    {
        if (!class_exists($modelClass)) {
            throw new \Exception(sprintf('{ %s } this model class not found', $modelClass));
        }
    }
}
