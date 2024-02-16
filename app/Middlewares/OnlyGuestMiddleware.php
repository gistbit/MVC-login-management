<?php
namespace MA\PHPMVC\Middlewares;

use MA\PHPMVC\App\Config;
use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;

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