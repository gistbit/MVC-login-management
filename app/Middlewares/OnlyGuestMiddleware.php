<?php
namespace App\Middlewares;

use App\Core\Config;
use App\Core\Http\Request;
use App\Core\Http\Response;

class OnlyGuestMiddleware implements Middleware
{
    function process(Request $request): bool
    {
        $user = $request->getSession(Config::get('session.name'), Config::get('session.key'));
        if ($user != null) {
            Response::redirect('/');
        }
        return true;
    }
}