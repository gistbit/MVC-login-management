<?php

use MA\PHPMVC\Controllers\HomeController;
use MA\PHPMVC\Controllers\UserController;
use MA\PHPMVC\Middlewares\{CSRFMiddleware, OnlyMemberMiddleware, OnlyGuestMiddleware, MustLoginAdmin};

$router->get('/', ['index', HomeController::class]);

$router->get("/user/register", ['register', UserController::class] , OnlyGuestMiddleware::class);
$router->post("/user/register", [UserController::class, 'postRegister'] , OnlyGuestMiddleware::class, CSRFMiddleware::class);

$router->get("/user/login", [UserController::class, 'login'], OnlyGuestMiddleware::class);
$router->post("/user/login", [UserController::class, 'postLogin'], OnlyGuestMiddleware::class, CSRFMiddleware::class);

$router->get("/user/logout", [UserController::class, 'logout'], OnlyMemberMiddleware::class);

$router->get("/user/profile", [UserController::class, 'updateProfile'], OnlyMemberMiddleware::class, MustLoginAdmin::class);
$router->post("/user/profile", [UserController::class, 'postUpdateProfile'], OnlyMemberMiddleware::class, CSRFMiddleware::class);

$router->get("/user/password", [UserController::class, 'updatePassword'], OnlyMemberMiddleware::class);
$router->post("/user/password", [UserController::class, 'postUpdatePassword'], OnlyMemberMiddleware::class, CSRFMiddleware::class);