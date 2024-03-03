<?php

use MA\PHPMVC\Middlewares\{CSRFMiddleware, OnlyMemberMiddleware, OnlyGuestMiddleware, MustLoginAdmin};

$router->get('/', 'HomeController@index');

$router->get("/user/register", "UserController@register", [OnlyGuestMiddleware::class]);
$router->post("/user/register", "UserController@postRegister", [OnlyGuestMiddleware::class, CSRFMiddleware::class]);

$router->get("/user/login", "UserController@login", [OnlyGuestMiddleware::class]);
$router->post("/user/login", "UserController@postLogin", [OnlyGuestMiddleware::class, CSRFMiddleware::class]);

$router->get("/user/logout", "UserController@logout", [OnlyMemberMiddleware::class]);

$router->get("/user/profile", "UserController@updateProfile", [OnlyMemberMiddleware::class, MustLoginAdmin::class]);
$router->post("/user/profile", "UserController@postUpdateProfile", [OnlyMemberMiddleware::class, CSRFMiddleware::class]);

$router->get("/user/password", "UserController@updatePassword", [OnlyMemberMiddleware::class]);
$router->post("/user/password", "UserController@postupdatePassword", [OnlyMemberMiddleware::class, CSRFMiddleware::class]);
