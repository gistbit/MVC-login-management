<?php

namespace MA\PHPMVC\Core;

class Config{

    private static array $config;

    public static function load()
    {
        self::$config = require(CONFIG . '/config.php');
    }

    public static function get($key, $default = null)
    {
        $parts = explode('.', $key);
        $value = self::$config;

        foreach ($parts as $part) {
            if (isset($value[$part])) {
                $value = $value[$part];
            } else {
                return $default;
            }
        }

        return $value;
    }
}