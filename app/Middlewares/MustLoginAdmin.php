<?php

namespace App\Middlewares;

use App\Core\Http\Request;

use function App\helper\response;
use App\Core\Config;

class MustLoginAdmin implements Middleware
{
    public function process(Request $request): bool
    {   
        $session = $request->getSession(Config::get('session.name'), Config::get('session.key'));
        
        if($this->isAdmin($session)){
            return true;
        }
        response()->setNotFound();
        return false;
    }

    public function isAdmin($session){
        return $session !== null && $session->role == 1;
    }
}