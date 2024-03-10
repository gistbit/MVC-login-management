<?php

use App\Domain\User;
use MA\PHPMVC\MVC\View;
use MA\PHPMVC\Application;
use App\Service\SessionService;
use MA\PHPMVC\Database\Database;
use MA\PHPMVC\Interfaces\Request;
use MA\PHPMVC\Interfaces\Response;
use App\Repository\SessionRepository;

function cetak($arr)
{
    echo '<pre>';
    print_r($arr);
    die;
    echo '</pre>';
}

function response(?string $content = null, int $code = 200): Response
{
    $response = Application::$response;
    if (!is_null($content) && !is_null($response)) {
        $response->setContent($content);
        $response->setStatusCode($code);
    }
    return $response;
}

function request(): Request
{
    return Application::$request;
}

function currentUser(): ?User
{
    $connection = Database::getConnection();
    $sessionRepository = new SessionRepository($connection);
    $sessionService = new SessionService($sessionRepository);
    return $sessionService->current();
}

function strRandom(int $length = 16): string
{
    return (function ($length) {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytesSize = (int) ceil($size / 3) * 3;

            $bytes = random_bytes($bytesSize);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    })($length);
}

function set_CSRF(string $path): string
{
    $token = strRandom(17);
    response()->setCookie('csrf_token', $token, time() + 60 * 60 * 30, $path);
    return $token;
}

function view(string $view, array $model = [], string $extends = '')
{
    return View::render($view, $model, $extends);
}
