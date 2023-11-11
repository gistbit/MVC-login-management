<?php

namespace MVC;

class Controller {


    protected $request;

    protected $response;

    protected $view;

    public function __construct() {
        $this->view = new View();
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    public function model($model) {
        $file = MODELS . "$model.php";

		// check exists file
        if (file_exists($file)) {
            require_once $file;
            
            if (class_exists($model))
                return new $model;
            else 
                throw new Exception(sprintf('{ %s } this model class not found', $model));
        } else {
            throw new Exception(sprintf('{ %s } this model file not found', $file));
        }
    }
   
}
