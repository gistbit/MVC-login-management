<?php

namespace MA\PHPMVC\Middlewares;

use MA\PHPMVC\App\Config;
use MA\PHPMVC\Core\Http\Request;
use function MA\PHPMVC\Helper\response;

class MustLoginAdmin implements Middleware
{
    public function process(Request $request): bool
    {   
        $session = $request->getSession(Config::get('session.name'), Config::get('session.key'));
        
        if($this->isAdmin($session)){
            return true;
        }
        return response()->setNotFound();
    }

    private function isAdmin($session): bool
    {
        return $session !== null && $session->role == 1;
    }
}