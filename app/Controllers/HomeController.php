<?php
namespace MA\PHPMVC\Controllers;

use MA\PHPMVC\Core\MVC\Controller;

use function MA\PHPMVC\Helper\currentUser;

class HomeController extends Controller {

    public function index() {
        if(currentUser() == null){
            return $this->renderView('home/index');
        }else{
            return $this->renderView('home/dashboard', [
                "title" => "Dashboard",
                "user" => [
                    "name" => currentUser()->name
                ]
            ]);
        }
    }
}