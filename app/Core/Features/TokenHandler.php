<?php

namespace App\Core\Features;
use Firebase\JWT\{JWT, Key};

class TokenHandler{

    private const ALGORITHM = 'HS256';

    public static function generateToken(array $payload, string $key): string
    {
        $JWT = JWT::encode($payload, $key, self::ALGORITHM );
        return $JWT;
    }

    public static function verifyToken(string $encPayload, string $key): ? \stdClass
    {
        try {
            $payload = JWT::decode($encPayload, new Key($key, self::ALGORITHM));
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }
}