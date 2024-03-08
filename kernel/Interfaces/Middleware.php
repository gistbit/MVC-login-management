<?php

namespace MA\PHPMVC\Interfaces;

use Closure;
use MA\PHPMVC\Interfaces\Request;

interface Middleware
{
    public function process(Request $request, Closure $next);
}
