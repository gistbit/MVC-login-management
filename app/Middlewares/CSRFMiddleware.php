<?php

namespace MA\PHPMVC\Middlewares;

use Closure;
use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Interfaces\Request;

class CSRFMiddleware implements Middleware
{
    public function process(Request $request, Closure $next)
    {  
        if($request->isMethod('post')){
            $token = $request->post('csrf_token') ?? '';
            if($token === $request->cookie('csrf_token')) return $next($request);
        }

        return response()->setNotFound('CSRF_TOKEN tidak valid !');
    }
}