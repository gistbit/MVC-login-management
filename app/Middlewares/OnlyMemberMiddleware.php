<?php

namespace App\Middlewares;

use App\Core\Http\Request;
use App\Core\Http\Response;

use function App\helper\userCurrent;

class OnlyMemberMiddleware implements Middleware
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