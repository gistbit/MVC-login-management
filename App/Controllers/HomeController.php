<?php
namespace App\Controllers;

use App\Core\MVC\Controller;

class HomeController extends Controller {

    public function index() {
        if($this->user == null){
            return $this->view->renderView('home/index');
        }else{
            return $this->view->renderView('home/dashboard', [
                "title" => "Dashboard",
                "user" => [
                    "name" => $this->user->name
                ]
            ]);
        }
    }
}
