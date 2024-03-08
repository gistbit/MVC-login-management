<?php

namespace App\Controllers;

use MA\PHPMVC\MVC\Controller;

class HomeController extends Controller
{

    public function index()
    {
        response()->setNoCache();
        if (currentUser() == null) {
            return $this->renderView('home/index');
        } else {
            return $this->renderView('home/dashboard', [
                "title" => "Dashboard",
                "user" => [
                    "name" => currentUser()->name
                ]
            ]);
        }
    }
}
