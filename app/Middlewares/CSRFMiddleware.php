<?php

namespace MA\PHPMVC\Middlewares;

use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Http\Request;

class CSRFMiddleware implements Middleware
{
    public function process(Request $request): bool
    {  
        if($request->isMethod('post')){
            $token = $request->post() ?? '';
            if($token === $request->cookie('csrf_token')) return true;
        }
        response('csrf_token tidak valid !');
        return false;
    }
}