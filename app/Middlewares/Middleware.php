<?php
namespace MA\PHPMVC\Middlewares;

use MA\PHPMVC\Core\Http\Request;

interface Middleware {
    public function process(Request $request): bool;
}
