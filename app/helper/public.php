<?php
namespace MA\PHPMVC\Helper;
use MA\PHPMVC\Domain\User;
use MA\PHPMVC\Core\Database\Database;
use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;
use MA\PHPMVC\Repository\SessionRepository;
use MA\PHPMVC\Service\SessionService;

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