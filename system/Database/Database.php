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

    public static function query($query)
    {
        self::$stmt = self::$pdo->prepare($query);
    }

    public static function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        self::$stmt->bindValue($param, $value, $type);
    }

    public static function execute()
    {
        if (self::$stmt) {
            self::$stmt->execute();
        }
    }

    public static function resultAll()
    {
        self::execute();
        return self::$stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function result()
    {
        self::execute();
        return self::$stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
