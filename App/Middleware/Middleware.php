<?php
namespace App\Middleware;

interface Middleware
{

    function before(Auth $auth): void;

}