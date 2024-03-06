<?php

namespace MA\PHPMVC\Core\Interfaces;

use MA\PHPMVC\Domain\User;

interface Request
{
    public function getSession(string $name, string $key): ? \stdClass;

    public function get(string $key = '');

    public function post(string $key = '');

    public function input(string $key = '');

    public function getPath(): string;

    public function getMethod(): string;

    public function cookie(string $key = '');

    public function files(string $key = '');

    public function header(string $key = ''): ?string;

    public function isMethod(string $method): bool;

    public function getClientIp(): ?string;

    public function isAjax(): bool;

    public function getUserAgent(): ?string;

    public function getQueryString(): string;

    public function user(): User;
}