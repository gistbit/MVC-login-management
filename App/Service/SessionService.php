<?php

namespace App\Service;

use App\Core\Http\Request;
use App\Repository\{SessionRepository, UserRepository};
use App\Domain\{User, Session};
use Firebase\JWT\JWT;


class SessionService
{
    public CONST COOKIE_NAME = "PHP-MVC";
    public CONST KEY = 'WjBGSFNHdHBhbXBLUkVwWlYxZFZNREk0T1RFd1NrWkxSa05PU2t0QlNVbFBTVWhKUVU5UFNUa3pPRk5CUm10elpHRmtZWE5yYW1wMmRYWTRNamt3TlRneU1qbHVjMnRxWm1Gb1lXeGhMSHB0ZUcxclkyWnBNVFk0TWprek1HNW1hR1k';

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

        $JWT = JWT::encode($payload, self::KEY, 'HS256');

        $this->sessionRepository->save($session);

        setcookie(self::COOKIE_NAME, $JWT, time() + (60 * 60 * 3), "/", "", false, true);

        return $session;
    }

    public function destroy()
    {
        $decoded = Request::currentSession();
        $this->sessionRepository->deleteById($decoded['id']);
        setcookie(self::COOKIE_NAME, '', 1, "/");
    }

    public function current(): ?User
    {
        $payload = Request::currentSession();
    
        if ($payload === null) {
            return null;
        }
    
        $session = $this->sessionRepository->findById($payload['id']);
    
        if ($session === null) {
            $this->destroy();
            return null;
        }

        $user = new User();
        $user->id = $session->userId;
        $user->name = $payload['name'];
        $user->role = $payload['role'];

        return $user;
        // return $this->userRepository->findById($session->userId);
    }  

}