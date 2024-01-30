<?php
namespace App\Middleware;

use App\Core\Http\Request;

class MiddlewareChain {
    private $middlewareList = [];

    public function addMiddleware(Middleware $middleware) {
        $this->middlewareList[] = $middleware;
    }

    public function processRequest(Request $request): bool {
        foreach ($this->middlewareList as $middleware) {
            if (!$middleware->process($request)) {
                // Tangani jika middleware mengembalikan false
                return false;
            }
        }
        return true; // Semua middleware berhasil diproses
    }
}