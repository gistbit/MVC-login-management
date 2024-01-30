<?php
namespace App\Middleware;

use App\Core\Http\Request;

interface Middleware {
    public function process(Request $request): bool;
}
