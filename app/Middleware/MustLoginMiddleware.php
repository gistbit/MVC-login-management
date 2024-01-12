<?php

namespace App\Middleware;
use App\Core\Database\Database;
use App\Repository\{SessionRepository, UserRepository};
use App\Service\{SessionService};

class MustLoginMiddleware implements Middleware
{
    private SessionService $sessionService;
    private \App\Core\Http\Response $response;

    private $admin = false; 

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
        if ($user == null) {
            $this->response->redirect('/user/login');
        }elseif($admin){
            if($user->level !== 1) $this->response->redirect('/');
        }
    }

    function setAdmin(){
        $this->admin = true;
        return $this;
    }
}