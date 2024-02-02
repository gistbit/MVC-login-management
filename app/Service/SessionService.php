<?php

namespace App\Service;

use App\Repository\SessionRepository;
use App\Domain\{User, Session};
use Firebase\JWT\JWT;
use App\Core\Config;
use Firebase\JWT\Key;
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

        $JWT = JWT::encode($payload, Config::get('session.key'), 'HS256');

        $this->sessionRepository->save($session);

        setcookie(Config::get('session.name'), $JWT, Config::get('session.exp'), "/", "", false, true);

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

        try {
            $payload = JWT::decode($JWT, new Key(Config::get('session.key'), 'HS256'));
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }

}