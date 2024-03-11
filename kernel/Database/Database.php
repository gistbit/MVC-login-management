<?php

namespace MA\PHPMVC\Database;

use MA\PHPMVC\Utility\Config;
use Exception;
use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    private static function initialize(): void
    {
        if (self::$pdo === null) {

            $db = Config::getDatabaseConfig();
            $dsn = sprintf("%s:host=%s;port=%s;dbname=%s", $db['driver'] ,$db['host'], $db['port'], $db['name']);
            
            try {
                self::$pdo = new PDO($dsn, $db['username'], $db['password']);
            } catch (PDOException $e) {
                throw new Exception('Koneksi ke basis data gagal: ' . $e->getMessage());
            }
        }
    }

    public static function getConnection(): PDO
    {
        self::initialize();
        return self::$pdo;
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction()
    {
        self::$pdo->commit();
    }

    public static function rollbackTransaction()
    {
        self::$pdo->rollBack();
    }
}
