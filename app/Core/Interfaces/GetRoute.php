<?php

namespace MA\PHPMVC\Core\Interfaces;

use MA\PHPMVC\Core\Router\Route;

interface GetRoute
{
    public function getRoute(string $method, string $path): ?Route;

    // public function getAllRoutes(): array;

}
