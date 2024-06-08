<?php

namespace App\Service;

use App\Service\UserService;
use App\Service\SessionService;
use MA\PHPMVC\Database\Database;
use App\Repository\UserRepository;
use App\Repository\SessionRepository;

trait ServiceTrait {

    private UserService $userService;
    private SessionService $sessionService;

    public function authService()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository);
    }
}