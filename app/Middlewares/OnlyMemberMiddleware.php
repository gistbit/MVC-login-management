<?php

namespace App\Middlewares;

use App\Core\Http\Request;
use App\Core\Http\Response;

use function App\Helper\currentUser;

class OnlyMemberMiddleware implements Middleware
{
    public function process(Request $request): bool
    {
        $user =  \App\Helper\currentUser();
        if ($user == null) {
            Response::redirect('/user/login');
        }
        return true;
    }
}