<?php

namespace App\Middleware;
use App\Core\Http\Response;

use function App\helper\userCurrent;

class MustLoginMiddleware implements Middleware
{

    private $admin = false;

    function before(): void
    {
        $user = userCurrent();
        if ($user == null) {
            Response::redirect('/user/login');
        }elseif($this->admin){
            if($user->role !== 1) Response::redirect('/');
        }
    }
    
    function setAdmin(){
        $this->admin = true;
        return $this;
    }
}