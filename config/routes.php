<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Middleware\{CSRFMiddleware, OnlyMemberMiddleware, OnlyGuestMiddleware, MustLoginAdmin};

$router->get('/', ['index', HomeController::class]);

$router->get("/user/register", ['showRegistration', AuthController::class] , OnlyGuestMiddleware::class);
$router->post("/user/register", ['register', AuthController::class] , OnlyGuestMiddleware::class, CSRFMiddleware::class);
$router->get("/user/login", [AuthController::class, 'showLogin'], OnlyGuestMiddleware::class);
$router->post("/user/login", [AuthController::class, 'login'], OnlyGuestMiddleware::class, CSRFMiddleware::class);
$router->get("/user/logout", [AuthController::class, 'logout'], OnlyMemberMiddleware::class);

$router->get("/user/profile", [ProfileController::class, 'edit'], OnlyMemberMiddleware::class, MustLoginAdmin::class);
$router->post("/user/profile", [ProfileController::class, 'update'], OnlyMemberMiddleware::class, CSRFMiddleware::class);
$router->get("/user/password", [ProfileController::class, 'changePassword'], OnlyMemberMiddleware::class);
$router->post("/user/password", [ProfileController::class, 'updatePassword'], OnlyMemberMiddleware::class, CSRFMiddleware::class);
