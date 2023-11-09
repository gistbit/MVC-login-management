<?php
use Database\Database;
class MustNotLoginMiddleware implements Middleware
{
    private SessionService $sessionService;
    private $response;

    public function __construct()
    {
        $this->response = $GLOBALS['response'];
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            $this->response->redirect('/');
        }
    }
}