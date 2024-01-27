<?php
namespace App\Middleware;
use App\Core\Http\Request;
use App\Core\Http\Response;

class MustNotLoginMiddleware implements Middleware
{
    function before(): void
    {
        $user = Request::currentSession();
        if ($user != null) {
            Response::redirect('/');
        }
    }
}