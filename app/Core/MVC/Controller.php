<?php

namespace App\Core\MVC;

use App\Core\Http\Request;
use App\Core\Http\Response;
use Exception;

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected View $view;

    public function __construct()
    {
        $this->view = new View();
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    /**
     * @throws Exception
     */
    public function model(string $modelName)
    {
        $modelClass = "\App\Models\\" . $modelName;

        $this->checkModelClass($modelClass);

        return new $modelClass;
    }

    /**
     * @throws Exception
     */
    private function checkModelClass(string $modelClass)
    {
        if (!class_exists($modelClass)) {
            throw new Exception(sprintf('{ %s } this model class not found', $modelClass));
        }
    }
}
