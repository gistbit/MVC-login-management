<?php

return [
    'app' => [
        'url' => 'http://www.localhost:8080/'
    ],

    'db' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'php_mvc',
        'username' => 'root',
        'password' => '',
        'prefix' => '',
        'sufix' => ''
    ],

    'session' => [
        'name' => 'PHP-MVC',
        'key' => "WjBGSFNHdHBhbXBLUkVwWlYxZFZNREk0T1RFd1NrWkxSa05PU2t0QlNVbFBTVWhKUVU5UFNUa3pPRk5CUm10elpHRmtZWE5yYW1wMmRYWTRNamt3TlRneU1qbHVjMnRxWm1Gb1lXeGhMSHB0ZUcxclkyWnBNVFk0TWprek1HNW1hR1k",
        'exp' => time() + ( 60 * 60 * 3) // 3 JAM
    ]
];
