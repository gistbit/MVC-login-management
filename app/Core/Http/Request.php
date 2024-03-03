<?php

namespace MA\PHPMVC\Core\Http;

use MA\PHPMVC\Core\Features\TokenHandler;

class Request
{
    private array $cookie;
    private array $files;

    public function __construct()
    {
        $this->cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
    }

    public function getSession(string $name, string $key): ? \stdClass
    {
        $JWT = $this->cookie[$name] ?? '';
        if (empty($JWT)) return null;
        return TokenHandler::verifyToken($JWT, $key);
    }

    public function get(string $key = '')
    {
        if($key !== '') return $this->getValue($_GET, $key);

        return $this->clean($_GET);
    }

    public function post(string $key = '')
    {
        if($key !== '') return $this->getValue($_POST, $key);

        return $this->clean($_POST);
    }

    public function input(string $key = '')
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);

        if($key !== '') return $this->getValue($request, $key);

        return $request;
    }

    public function getPath(): string
    {
        return $this->getValue($_SERVER, 'PATH_INFO', '/');
    }

    public function getMethod(): string
    {
        return $this->getValue($_SERVER, 'REQUEST_METHOD', 'GET');
    }

    public function cookie(string $key = '')
    {
        if($key !== '') return $this->getValue($this->cookie, $key);
        return $this->clean($this->cookie);
    }

    public function files(string $key = '')
    {
        if($key !== '') return $this->getValue($this->files, $key);
        return $this->files;
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

}
