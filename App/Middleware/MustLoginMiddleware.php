<?php

namespace App\Middleware;
use App\Core\Database\Database;
use App\Core\Http\Response;
use App\Repository\{SessionRepository, UserRepository};
use App\Service\{SessionService};

class MustLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    private $admin = false; 

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user == null) {
            Response::redirect('/user/login');
        }elseif($this->admin){
            if($user->role !== 1) Response::redirect('/');
        }
    }
    
    function setAdmin(){
        $this->admin = true;
        return $this;
    }
}