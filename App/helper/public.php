<?php
namespace App\helper;
use App\Domain\User;
use App\Core\Database\Database;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Repository\{SessionRepository, UserRepository};
use App\Service\SessionService;

function cetak($arr){
    echo '<pre>';
        print_r($arr);
    echo '</pre>';
}

function response() : Response
{
    static $instance = null;
    if($instance === null){
      return new Response();
    }
    return $instance;
}

function request() : Request
{
    static $instance = null;
    if($instance === null){
      return new Request();
    }
    return $instance;
}

function userCurrent(): ?User
{
    $connection = Database::getConnection();
    $sessionRepository = new SessionRepository($connection);
    $userRepository = new UserRepository($connection);
    $sessionService = new SessionService($sessionRepository, $userRepository);
    return $sessionService->current();
}