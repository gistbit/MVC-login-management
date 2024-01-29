<?php

namespace App\Middleware;
use App\Core\Http\Response;

use function App\helper\userCurrent;

class MustLoginAdminMiddleware implements Middleware
{

    function before(): void
    {
        $user = userCurrent();
        if ($user == null) {
            Response::redirect('/user/login');
        }elseif($user->role !== 1){
            Response::redirect('/');
        }
    }
}