<?php

namespace App\Middleware;
use App\Core\Http\Request;
use App\Core\Http\Response;

class MustLoginMiddleware implements Middleware
{
    private $admin = false; 

    function before(): void
    {
        $user = Request::currentSession();
        if ($user == null) {
            Response::redirect('/user/login');
        }elseif($this->admin){
            if($user['role'] !== 1) Response::redirect('/');
        }
    }
    
    function setAdmin(){
        $this->admin = true;
        return $this;
    }
}