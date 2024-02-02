<?php

namespace App\Middlewares;

use App\Core\Http\Request;

use function App\helper\response;
use App\Core\Config;

class MustLoginAdmin implements Middleware
{
    function process(Request $request): bool
    {   
        $session = $request->getSession(Config::get('session.name'), Config::get('session.key'));
        if($session !== null && $session->role == 1){
            return true;
        }
        response()->setNotFound();
        return false;
    }
}