<?php
namespace App\Middlewares;

use App\Core\Http\Request;
use App\Core\Http\Response;

class MustNotLoginMiddleware implements Middleware
{
    function process(Request $request): bool
    {
        $user = $request->currentSession();
        if ($user != null) {
            Response::redirect('/');
        }
        return true;
    }
}