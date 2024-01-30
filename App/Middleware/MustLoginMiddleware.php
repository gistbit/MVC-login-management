<?php

namespace App\Middleware;

use App\Core\Http\Request;
use App\Core\Http\Response;

use function App\helper\userCurrent;

class MustLoginMiddleware implements Middleware
{
    function process(Request $request): bool
    {
        $user =  userCurrent();
        if ($user == null) {
            Response::redirect('/user/login');
        }
        return true;
    }
}