<?php
namespace App\Middleware;
use App\Core\Database\Database;
use App\Core\Http\Response;
use App\Repository\{SessionRepository, UserRepository};
use App\Service\{SessionService};


class MustNotLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            Response::redirect('/');
        }
    }
}