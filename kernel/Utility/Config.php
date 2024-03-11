<?php

namespace MA\PHPMVC\Utility;

class Config
{
    public static function isDevelopmentMode(): bool
    {
        return self::get('mode.development', false);
    }

    public static function getDatabaseConfig(): array
    {
        return self::get('database', []);
    }

    public static function get($key, $default = null)
    {
        $config = require(CONFIG . '/config.php');
        $keys = explode('.', $key);

        foreach ($keys as $part) {
            if (isset($config[$part])) {
                $config = $config[$part];
            } else {
                return $default;
            }
        }

        return $config;
    }
}
