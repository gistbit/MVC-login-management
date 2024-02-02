<?php
namespace App\Helper;
use App\Domain\User;
use App\Core\Database\Database;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Repository\SessionRepository;
use App\Service\SessionService;

function cetak($arr){
    echo '<pre>';
        print_r($arr);die;
    echo '</pre>';
}

function response() : Response
{
    return $GLOBALS['response'];
}

function request() : Request
{
    return $GLOBALS['request'];
}

function currentUser(): ?User
{
    $connection = Database::getConnection();
    $sessionRepository = new SessionRepository($connection);
    $sessionService = new SessionService($sessionRepository);
    return $sessionService->current();
}