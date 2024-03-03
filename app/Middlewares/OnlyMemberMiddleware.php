<?php

namespace MA\PHPMVC\Middlewares;

use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;

class OnlyMemberMiddleware implements Middleware
{
    public function process(Request $request): bool
    {
        $user = currentUser();
        if ($user == null) {
            Response::redirect('/user/login');
        }
        return true;
    }
}