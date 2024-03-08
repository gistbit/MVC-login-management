<?php

namespace App\Middleware;

use Closure;
use MA\PHPMVC\Utility\Config;
use MA\PHPMVC\Interfaces\Middleware;
use MA\PHPMVC\Interfaces\Request;

class OnlyGuestMiddleware implements Middleware
{
    public function process(Request $request, Closure $next)
    {
        $user = $request->getSession(Config::get('session.name'), Config::get('session.key'));
        if ($user != null) {
            response()->redirect('/');
        }
        return $next($request);
    }
}
