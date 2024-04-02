<?php

namespace MA\PHPMVC\Utility;

class Config
{
    protected static $config;

    public static function isDevelopmentMode(): bool
    {
        return self::get('mode.development', false);
    }

    public static function getDatabaseConfig(): array
    {
        return self::get('database', []);
    }

    protected static function loadConfig()
    {
        if (!isset(self::$config)) {
            self::$config = require(CONFIG . '/config.php');
        }
    }

    public static function get($key, $default = null)
    {
        self::loadConfig();

        $config = self::$config;
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