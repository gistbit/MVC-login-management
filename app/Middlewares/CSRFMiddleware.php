<?php

namespace MA\PHPMVC\Middlewares;

use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Http\Request;

class CSRFMiddleware implements Middleware
{
    public function process(Request $request): bool
    {   
        if($request->isMethod('post')){
            $token = $request->post('csrf_token') ?? '';
            if($token === $request->cookie('csrf_token')) return true;
        }
        response()->redirect('/');
        return false;
    }
}