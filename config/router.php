<?php

use App\Core\MVC\View;
use App\Middlewares\{OnlyMemberMiddleware, OnlyGuestMiddleware, MustLoginAdmin};

use function App\helper\response;
use App\Core\Http\Response;

$router->get('/', 'HomeController@index');

$router->get("/user/register", "UserController@register", [OnlyGuestMiddleware::class]);
$router->post("/user/register", "UserController@postRegister", [OnlyGuestMiddleware::class]);

$router->get("/user/login", "UserController@login", [OnlyGuestMiddleware::class]);
$router->post("/user/login", "UserController@postLogin", [OnlyGuestMiddleware::class]);

$router->get("/user/logout", "UserController@logout", [OnlyMemberMiddleware::class]);

$router->get("/user/profile", "UserController@updateProfile", [OnlyMemberMiddleware::class, MustLoginAdmin::class]);
$router->post("/user/profile", "UserController@postUpdateProfile", [OnlyMemberMiddleware::class]);

$router->get("/user/password", "UserController@updatePassword", [OnlyMemberMiddleware::class]);
$router->post("/user/password", "UserController@postupdatePassword", [OnlyMemberMiddleware::class]);

$router->get($request->getPath(), function() {
    response()->setStatus(404);
    return View::renderViewOnly(404, [
        'title' => 'error',
        'status' => [
            'code' => 404,
            'text' => Response::STATUS_TEXTS[404]
        ]
    ]);
});