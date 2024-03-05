<?php

namespace MA\PHPMVC\Core\Interfaces;

interface Routes
{
    public function get($path, $callback, $middlewares = []);

    public function post($path, $callback, $middlewares = []);

    public function put($path, $callback, $middlewares = []);

    public function delete($path, $callback, $middlewares = []);
}
