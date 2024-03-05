<?php
namespace MA\PHPMVC\Controllers;

use MA\PHPMVC\Core\MVC\Controller;

class HomeController extends Controller {

    public function index() {
        response()->setNoCache();
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