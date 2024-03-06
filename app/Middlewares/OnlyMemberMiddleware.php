<?php

namespace MA\PHPMVC\Middlewares;

use Closure;
use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Interfaces\Request;
use MA\PHPMVC\Core\Http\Response;

class OnlyMemberMiddleware implements Middleware
{
    public function process(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user == null) {
            Response::redirect('/user/login');
        }
        return $next($request);
    }
}