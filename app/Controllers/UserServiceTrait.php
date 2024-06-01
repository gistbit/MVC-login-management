<?php

namespace App\Controllers;

use App\Service\UserService;
use App\Service\SessionService;
use MA\PHPMVC\Database\Database;
use App\Repository\UserRepository;
use App\Repository\SessionRepository;

trait UserServiceTrait {

    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository);
    }
}