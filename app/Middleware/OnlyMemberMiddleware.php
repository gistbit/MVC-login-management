<?php

namespace App\Middleware;

use Closure;
use MA\PHPMVC\Interfaces\Middleware;
use MA\PHPMVC\Interfaces\Request;

class OnlyMemberMiddleware implements Middleware
{
    public function execute(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user == null) {
            response()->redirect('/user/login');
        }
        return $next($request);
    }
}
