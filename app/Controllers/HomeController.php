<?php

namespace App\Controllers;

use MA\PHPMVC\Interfaces\Request;
use MA\PHPMVC\MVC\Controller;

class HomeController extends Controller
{
    protected $template = 'layouts/home';

    public function index(Request $request)
    {
        response()->setNoCache();
        if ($request->user() == null) {
            return $this->view('index');
        } else {
            return $this->home();
        }
    }

    public function home()
    {
        return $this->view('home/index', [
            "title" => "Dashboard",
            "user" => [
                "name" => currentUser()->name
            ]
        ]);
    }
}