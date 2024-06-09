<?php

namespace MA\PHPMVC\Interfaces;

use MA\PHPMVC\Interfaces\Request;

interface Middleware
{
    public function execute(Request $request, callable $next);
}
