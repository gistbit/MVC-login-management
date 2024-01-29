<?php

namespace App\Middleware;
use App\Core\Http\Response;

use function App\helper\userCurrent;

class MustLoginMiddleware implements Middleware
{

    function before(): void
    {
        $user = userCurrent();
        if ($user == null) {
            Response::redirect('/user/login');
        }
    }
}