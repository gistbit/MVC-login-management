<?php

namespace App\Controllers;

use MA\PHPMVC\Interfaces\Request;
use MA\PHPMVC\MVC\Controller;

class HomeController extends Controller
{
    protected $template = 'layouts/app';

    public function index(Request $request)
    {
        response()->setNoCache();
        if ($request->user() == null) {
            return view('welcome');
        } else {
            return $this->home($request->user());
        }
    }

    private function home($user)
    {
        return $this->view('home/index', [
            "title" => "Dashboard",
            "user" => [
                "name" => $user->name
            ]
        ]);
    }
}