<?php

namespace MA\PHPMVC\Middlewares;

use Closure;
use MA\PHPMVC\Core\Utility\Config;
use MA\PHPMVC\Core\Interfaces\Middleware;
use MA\PHPMVC\Core\Interfaces\Request;

class MustLoginAdmin implements Middleware
{
    public function process(Request $request, Closure $next)
    {   
        $session = $request->getSession(Config::get('session.name'), Config::get('session.key'));
        
        if($this->isAdmin($session)){
            return $next($request);
        }
        return response()->setNotFound();
    }

    private function isAdmin($session): bool
    {
        return $session !== null && $session->role == 1;
    }
}