<?php

use App\Middleware\{MustLoginMiddleware, MustNotLoginMiddleware};

$router->get('/', 'homeController@index');

$router->get("/user/register", "UserController@register", [MustNotLoginMiddleware::class]);
$router->post("/user/register", "UserController@postRegister", [MustNotLoginMiddleware::class]);

$router->get("/user/login", "UserController@login", [MustNotLoginMiddleware::class]);
$router->post("/user/login", "UserController@postLogin", [MustNotLoginMiddleware::class]);

$router->get("/user/logout", "UserController@logout", [MustLoginMiddleware::class]);

$router->get("/user/profile", "UserController@updateProfile", [MustLoginMiddleware::class]);
$router->post("/user/profile", "UserController@postUpdateProfile", [MustLoginMiddleware::class]);

$router->get("/user/password", "UserController@updatePassword", [MustLoginMiddleware::class]);
$router->post("/user/password", "UserController@postupdatePassword", [MustLoginMiddleware::class]);


$router->get('/:redirect', function() {
    $GLOBALS['response']->redirect("/");
});


$router->get('/:name', function($param) {
    echo 'aku '. $param['name'];
});



