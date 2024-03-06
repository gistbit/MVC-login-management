<?php
namespace MA\PHPMVC\Core\Interfaces;

use Closure;
use MA\PHPMVC\Core\Interfaces\Request;

interface Middleware {
    public function process(Request $request, Closure $next);
}
