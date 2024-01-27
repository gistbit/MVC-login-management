<?php

namespace App\Service;
use App\Repository\{SessionRepository, UserRepository};
use App\Domain\{User, Session};
use Firebase\JWT\{JWT, Key};


class SessionService
{

    public CONST COOKIE_NAME = "PHP-MVC";
    private CONST KEY = "gAGHkijjJDJYWWU028910JFKFCNJKAIIOIHIAOOI938SAFksdadaskjjvuv829058229nskjfahala,zmxmkcfi1682930nfhf";

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
            'username' => $user->id,
            'role' => $user->role
        ];

        $JWT = JWT::encode($payload, self::KEY, 'HS256');

        $this->sessionRepository->save($session);

        setcookie(self::COOKIE_NAME, $JWT, time() + (60 * 60 * 3), "/", "", false, true);

        return $session;
    }

    public function destroy()
    {
        $JWT = $_COOKIE[self::COOKIE_NAME] ?? '';
        
        $decoded = JWT::decode($JWT, new Key(self::KEY, 'HS256'));

        $this->sessionRepository->deleteById($decoded->id);

        setcookie(self::COOKIE_NAME, '', 1, "/");
    }

    public function current(): ?User
    {
        $JWT = $_COOKIE[self::COOKIE_NAME] ?? '';
        
        if(empty($JWT)) return null;

        try {
            $payload = JWT::decode($JWT, new Key(self::KEY, 'HS256'));
            $session = $this->sessionRepository->findById($payload->id);
            if($session == null) return null;

            // $user = new User;
            // $user->id = $payload->username;
            // $user->name ='';
            // $user->role = $payload->role;
            // return $user;

            return $this->userRepository->findById($payload->username);

        } catch (\Exception $e) {
            return null;
        }
    }

}