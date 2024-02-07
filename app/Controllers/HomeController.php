<?php
namespace App\Controllers;

use App\Core\MVC\Controller;
use App\Core\MVC\View;

use function App\Helper\currentUser;

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