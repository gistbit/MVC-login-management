<?php

namespace App\Core\Http;

use App\Core\Features\TokenHandler;

class Request
{
    private static array $cookie;
    private array $files;

    public function __construct()
    {
        self::$cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
    }

    public function getSession(string $name, string $key = 'key'): ? \stdClass
    {
        $JWT = self::$cookie[$name] ?? '';
        if (empty($JWT)) return null;
        return TokenHandler::verifyToken($JWT, $key);
    }

    public function get(string $key = ''): ?string
    {
        return $this->getValue($_GET, $key);
    }

    public function post(string $key = ''): ?string
    {
        return $this->getValue($_POST, $key);
    }

    public function input(string $key = ''): ?string
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);

        return $this->getValue($request, $key);
    }

    public function getPath(): string
    {
        return $this->getValue($_SERVER, 'PATH_INFO', '/');
    }

    public function getMethod(): string
    {
        return $this->getValue($_SERVER, 'REQUEST_METHOD', 'GET');
    }

    public function has(string $key): bool
    {
        return $this->hasKey($_GET, $key) || $this->hasKey($_POST, $key);
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    public function only(array $keys): array
    {
        $data = [];

        foreach ($keys as $key) {
            $value = $this->get($key);
            if ($value !== null) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function cookie(string $key = ''): ?string
    {
        return $this->getValue(self::$cookie, $key);
    }

    public function files(string $key = ''): ?array
    {
        return $this->getValue($this->files, $key);
    }

    public function header(string $key = ''): ?string
    {
        return $this->getValue(getallheaders(), $key);
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($this->getMethod()) === strtoupper($method);
    }

    public function getClientIp(): ?string
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    public function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function getUserAgent(): ?string
    {
        return $this->header('User-Agent');
    }

    public function getQueryString(): string
    {
        return $_SERVER['QUERY_STRING'] ?? '';
    }

    private function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }

    private function getValue(array $array, string $key, string $default = null): ?string
    {
        return isset($array[$key]) ? $this->clean($array[$key]) : $default;
    }

    private function hasKey(array $array, string $key): bool
    {
        return isset($array[$key]);
    }
}
