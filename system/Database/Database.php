<?php

namespace Database;

class Database
{
    private static ?\PDO $pdo = null;
    private static $stmt = null;

    public static function getConnection(): \PDO
    {
        if (self::$pdo === null) {
            $config = json_decode(file_get_contents(ROOT . "/config/database.json"), true);
            $url = "mysql:host=" . $config['database']['host'] . ";port=" . $config['database']['port'] . ";dbname=" . $config['database']['dbname'];
            try {
                self::$pdo = new \PDO($url, $config['database']['dbuser'], $config['database']['dbpass']);
            } catch (\PDOException $e) {
                throw new \Exception('Koneksi ke basis data gagal: ' . $e->getMessage());
            }
        }
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
