<?php

use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Middleware\{CSRFMiddleware, OnlyMemberMiddleware, OnlyGuestMiddleware, MustLoginAdmin};
use MA\PHPMVC\Router\Router;

Router::get('/', 'HomeController@index');

Router::get("/user/register", [AuthController::class, 'showRegistration'] , OnlyGuestMiddleware::class);
Router::post("/user/register", [AuthController::class, 'register'] , OnlyGuestMiddleware::class, CSRFMiddleware::class);
Router::get("/user/login", [AuthController::class, 'showLogin'], OnlyGuestMiddleware::class);
Router::post("/user/login", [AuthController::class, 'login'], OnlyGuestMiddleware::class, CSRFMiddleware::class);
Router::get("/user/logout", [AuthController::class, 'logout'], OnlyMemberMiddleware::class);

Router::get("/user/profile", [ProfileController::class, 'edit'], OnlyMemberMiddleware::class, MustLoginAdmin::class);
Router::post("/user/profile", [ProfileController::class, 'update'], OnlyMemberMiddleware::class, CSRFMiddleware::class);
Router::get("/user/password", [ProfileController::class, 'changePassword'], OnlyMemberMiddleware::class);
Router::post("/user/password", [ProfileController::class, 'updatePassword'], OnlyMemberMiddleware::class, CSRFMiddleware::class);
