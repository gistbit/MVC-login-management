<?php

namespace App\Core\Database;
use App\App\Config;
use Exception;
use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    private static function initialize(): void
    {
        if (self::$pdo === null) {

            $dsn = "mysql:host=" . Config::get('db.host') . ";port=" . Config::get('db.port') . ";dbname=" . Config::get('db.name');

            try {
                self::$pdo = new PDO($dsn, Config::get('db.username'), Config::get('db.password'));
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
