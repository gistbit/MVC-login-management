<?php
namespace App\Middlewares;

use App\App\Config;
use App\Core\Http\Request;
use App\Core\Http\Response;

class OnlyGuestMiddleware implements Middleware
{
    public function process(Request $request): bool
    {
        $user = $request->getSession(Config::get('session.name'), Config::get('session.key'));
        if ($user != null) {
            Response::redirect('/');
        }
        return true;
    }
}