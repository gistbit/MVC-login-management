<?php

namespace App\Service;

use App\Core\Http\Request;
use App\Repository\{SessionRepository, UserRepository};
use App\Domain\{User, Session};
use Firebase\JWT\JWT;
use App\Core\Config;


class SessionService
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(User $user): Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;

        $payload = [
            'id' => $session->id,
            'name' => $user->name,
            'role' => $user->role
        ];

        $JWT = JWT::encode($payload, Config::get('session.key'), 'HS256');

        $this->sessionRepository->save($session);

        setcookie(Config::get('session.name'), $JWT, Config::get('session.exp'), "/", "", false, true);

        return $session;
    }

    public function destroy()
    {
        $session = Request::currentSession();
        $this->sessionRepository->deleteById($session->id);
        setcookie(Config::get('session.name'), '', 1, "/");
    }

    public function current(): ?User
    {
        $payload = Request::currentSession();
    
        if ($payload === null) {
            return null;
        }
    
        $session = $this->sessionRepository->findById($payload->id);
    
        if ($session === null) {
            $this->destroy();
            return null;
        }

        $user = new User();
        $user->id = $session->userId;
        $user->name = $payload->name;
        $user->role = $payload->role;

        return $user;
        // return $this->userRepository->findById($session->userId);
    }  

}