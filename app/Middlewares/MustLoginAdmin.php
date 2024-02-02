<?php

namespace App\Middlewares;

use App\Core\Http\Request;

use function App\helper\response;

class MustLoginAdmin implements Middleware
{
    function process(Request $request): bool
    {
        $session = $request->currentSession();
        if($session !== null && $session->role == 1){
            return true;
        }
        response()->setNotFound();
        return false;
    }
}