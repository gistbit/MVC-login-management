<?php
namespace MA\PHPMVC\Controllers;

use MA\PHPMVC\Core\MVC\Controller;
use MA\PHPMVC\Core\MVC\View;

use function MA\PHPMVC\Helper\currentUser;

class HomeController extends Controller {

    public function index() {
        if(currentUser() == null){
            return $this->view->renderView('home/index');
        }else{
            return View::renderView('home/dashboard', [
                "title" => "Dashboard",
                "user" => [
                    "name" => currentUser()->name
                ]
            ]);
        }
    }
}