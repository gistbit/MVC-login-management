<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Middleware\{CSRFMiddleware, OnlyMemberMiddleware, OnlyGuestMiddleware, MustLoginAdmin};
use MA\PHPMVC\Router\Router;

Router::get('/', ['index', HomeController::class]);

Router::get("/user/register", ['showRegistration', AuthController::class] , OnlyGuestMiddleware::class);
Router::post("/user/register", ['register', AuthController::class] , OnlyGuestMiddleware::class, CSRFMiddleware::class);
Router::get("/user/login", [AuthController::class, 'showLogin'], OnlyGuestMiddleware::class);
Router::post("/user/login", [AuthController::class, 'login'], OnlyGuestMiddleware::class, CSRFMiddleware::class);
Router::get("/user/logout", [AuthController::class, 'logout'], OnlyMemberMiddleware::class);

Router::get("/user/profile", [ProfileController::class, 'edit'], OnlyMemberMiddleware::class, MustLoginAdmin::class);
Router::post("/user/profile", [ProfileController::class, 'update'], OnlyMemberMiddleware::class, CSRFMiddleware::class);
Router::get("/user/password", [ProfileController::class, 'changePassword'], OnlyMemberMiddleware::class);
Router::post("/user/password", [ProfileController::class, 'updatePassword'], OnlyMemberMiddleware::class, CSRFMiddleware::class);
