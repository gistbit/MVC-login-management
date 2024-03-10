<?php

return [
    'app' => [
        'url' => 'http://www.localhost:8080/'
    ],

    'mode' => [
        'development' => true
    ],

    'db' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'php_mvc',
        'username' => 'root',
        'password' => '',
        'prefix' => ''
    ],

    'session' => [
        'name' => 'PHP-MVC',
        'key' => "kRd9SO75b0MffA6ThNjW0lYfZpUJzwbiwN9moDf0wQvyLWmBdrnYbCZ4IekHQVNenFD8gt4sKreL7ZV0bUR32I5j8NexHITW5PTTHkm6GqjezcitHuQRfD1I3v1r7xgG2MDZWvLEmMF8140u1jtiyODLPIxU00mYMFfoyP0pRcQKeKx8NOKzPNmBYYNCB97xEHTZ6d1cJWipsg5a7k3ZkWWpXivBaWXHcdZ9E34al4EHY2DVgWwYHDtQ3jUSjuG8",
        'exp' => time() + ( 60 * 60 * 3) // 3 JAM
    ]
];
