<?php
namespace App\Middleware;
use App\Core\Http\Response;

use function App\helper\userCurrent;

class MustNotLoginMiddleware implements Middleware
{
    function before(Auth $auth = null): void
    {
        $user = userCurrent();
        if ($user != null) {
            Response::redirect('/');
        }
    }
}