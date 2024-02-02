<?php

namespace App\Service;

use App\Repository\SessionRepository;
use App\Domain\{User, Session};
use App\Core\Config;
use App\Core\Features\Secret;
use stdClass;

class SessionService
{
    private SessionRepository $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
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

        $this->sessionRepository->save($session);

        $value = Secret::encode($payload, Config::get('session.key'));
        setcookie(Config::get('session.name'), $value, Config::get('session.exp'), "/", "", false, true);

        return $session;
    }

    public function destroy()
    {
        $session = $this->payload();
        $this->sessionRepository->deleteById($session->id);
        setcookie(Config::get('session.name'), '', 1, "/");
    }

    public function current(): ?User
    {
        $payload = $this->payload();
    
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
    }  

    private function payload(): ?stdClass
    {
        $JWT = $_COOKIE[Config::get('session.name')] ?? '';
        if (empty($JWT)) {
            return null;
        }
        return Secret::decode($JWT, Config::get('session.key'));
    }

}