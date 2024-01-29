<?php

namespace App\Middleware;
use App\Core\Http\Response;

use function App\helper\userCurrent;

class MustLoginMiddleware implements Middleware
{

    function before(Auth $auth = null): void
    {
        $user = userCurrent();
        if ($user == null) {
            Response::redirect('/user/login');
        }elseif($auth->isAdmin()){
            if($user->role !== 1) Response::redirect('/');
        }
    }
}