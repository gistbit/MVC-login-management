<?php

namespace App\Middleware;

use MA\PHPMVC\Interfaces\Middleware;
use MA\PHPMVC\Interfaces\Request;

class OnlyGuestMiddleware implements Middleware
{
    public function execute(Request $request, callable $next)
    {
        $user = $request->user();
        if ($user != null) {
            response()->redirect('/');
        }
        return $next($request);
    }
}
