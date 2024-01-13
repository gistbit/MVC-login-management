<?php
namespace App\Controllers;

use App\Core\MVC\Controller;

class HomeController extends Controller {

    public function index() {
        if($this->user == null){
            $html = $this->view->renderView('home/index');
            $this->response->setContent($html);
        }else{
            $this->dashboard();            
        }        
    }

    private function dashboard() {
        $html = $this->view->renderView('home/dashboard', [
            "title" => "Dashboard",
            "user" => [
                "name" => $this->user->name
            ]
        ]);
        $this->response->setContent($html);
    }
    
}
