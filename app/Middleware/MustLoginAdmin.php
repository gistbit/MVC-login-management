<?php

namespace App\Middleware;

use MA\PHPMVC\Interfaces\Middleware;
use MA\PHPMVC\Interfaces\Request;

class MustLoginAdmin implements Middleware
{
    public function execute(Request $request, callable $next)
    {
        $session = $request->user();

        if ($this->isAdmin($session)) {
            return $next($request);
        }
        return response()->setForbidden();
    }

    private function isAdmin($session): bool
    {
        return $session !== null && $session->role == 1;
    }
}
