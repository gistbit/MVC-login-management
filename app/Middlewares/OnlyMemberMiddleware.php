<?php

namespace MA\PHPMVC\Middlewares;

use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Interfaces\Request;
use MA\PHPMVC\Core\Interfaces\Response;

class OnlyMemberMiddleware implements Middleware
{
    public function process(Request $request): bool
    {
        $user = $request->user();
        if ($user == null) {
            Response::redirect('/user/login');
        }
        return true;
    }
}