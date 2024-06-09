<?php

namespace  App\Service;

use MA\PHPMVC\Utility\Config;
use MA\PHPMVC\Utility\TokenHandler;
use App\Domain\{Session, User};
use App\Repository\SessionRepository;

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
        $session->id = strRandom(10);
        $session->userId = $user->id;
        
        $this->sessionRepository->save($session);
        $this->setSessionCookie($user, $session->id);

        return $session;
    }

    public function destroy()
    {
        $session = $this->getSessionPayload();
        if($session){
            $this->sessionRepository->deleteById($session->id);
            // $this->sessionRepository->deleteAll();
            $this->clearSessionCookie();
        }
    }

    public function current(): ?User
    {
        $payload = $this->getSessionPayload();

        if ($this->isSessionExpired($payload)) {
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

    private function isSessionExpired($payload): bool
    {
        return $payload === null || $payload->exp < time();
    }

    private function getSessionPayload() : ?\stdClass
    {
        $JWT = request()->cookie(Config::get('session.name')) ?? '';
        if (empty($JWT)) return null;
        return TokenHandler::verifyToken($JWT, Config::get('session.key'));
    }

    private function setSessionCookie(User $user, string $sessionId): void
    {
        $expires = Config::get('session.exp');

        $payload = [
            'id' => $sessionId,
            'name' => $user->name,
            'role' => $user->role,
            'exp' => $expires
        ];

        $value = TokenHandler::generateToken($payload, Config::get('session.key'));
        setcookie(Config::get('session.name'), $value, $expires, "/", "", false, true);
    }

    private function clearSessionCookie(): void
    {
        setcookie(Config::get('session.name'), '', 1, "/");
    }

}
