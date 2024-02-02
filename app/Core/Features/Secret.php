<?php

namespace App\Core\Features;
use Firebase\JWT\{JWT, Key};

class Secret{

    public static function encode(array $payload, string $key): string
    {
        $JWT = JWT::encode($payload, $key, 'HS256');
        return $JWT;
    }

    public static function decode(string $encPayload, string $key): ? \stdClass
    {
        try {
            $payload = JWT::decode($encPayload, new Key($key, 'HS256'));
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }
}