<?php

use App\Core\Http\Request;
use App\Core\MVC\View;
use App\Middleware\{MustLoginMiddleware, MustNotLoginMiddleware, Role};

use function App\helper\response;
use App\Core\Http\Response;

$router->get('/', 'homeController@index');

$router->get("/user/register", "UserController@register", [MustNotLoginMiddleware::class]);
$router->post("/user/register", "UserController@postRegister", [MustNotLoginMiddleware::class]);

$router->get("/user/login", "UserController@login", [MustNotLoginMiddleware::class]);
$router->post("/user/login", "UserController@postLogin", [MustNotLoginMiddleware::class]);

$router->get("/user/logout", "UserController@logout", [MustLoginMiddleware::class]);

$router->get("/user/profile", "UserController@updateProfile", [MustLoginMiddleware::class, Role::ADMIN]);
$router->post("/user/profile", "UserController@postUpdateProfile", [MustLoginMiddleware::class]);

$router->get("/user/password", "UserController@updatePassword", [MustLoginMiddleware::class]);
$router->post("/user/password", "UserController@postupdatePassword", [MustLoginMiddleware::class]);

$router->get($request->getPath(), function(Request $request) {
    response()->setStatus(404);
    return View::renderViewOnly(404, [
        'path' => $request->getPath(), 
        'title' => 'error',
        'status' => [
            'code' => 404,
            'text' => Response::STATUS_TEXTS[404]
        ]
    ]);
});