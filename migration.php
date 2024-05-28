<?php

define('CONFIG', __DIR__ . '/config');
require_once __DIR__ . '/vendor/autoload.php';

use MA\PHPMVC\Database\Database;

// Inisialisasi koneksi database
$db = Database::getConnection();

interface Migration
{
    public function version(): int;
    public function migrate();
}

function runMigration(Migration $migration, int $existingVersion)
{
    global $db;

    if ($migration->version() > $existingVersion) {
        try{
            Database::beginTransaction();
            $migration->migrate();
            $db->exec("INSERT INTO `version` (`id`) VALUES ({$migration->version()})");
            Database::commitTransaction();
        }catch(\PDOException $e){
            Database::rollbackTransaction();
            echo $e->getMessage();
        }
    }
}

function getExistingVersion(): int
{
    global $db;
    $db->exec("CREATE TABLE IF NOT EXISTS version(
        id int NOT NULL
    ) ENGINE=InnoDB");
    $result = $db->query("SELECT MAX(id) as version FROM `version`")->fetch();
    return $result['version'] ?? 0;
}


//class Migration

class Migration01 implements Migration
{
    public function version(): int
    {
        return 1;
    }

    public function migrate()
    {
        global $db;
        // Buat tabel users
        $db->exec("CREATE TABLE users (
            id VARCHAR(255) PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            role INT NOT NULL
        ) ENGINE=InnoDB");
    }
}

class Migration02 implements Migration
{
    public function version(): int
    {
        return 2;
    }

    public function migrate()
    {
        global $db;
        // Buat tabel sessions
        $db->exec("CREATE TABLE sessions (
            id VARCHAR(255) PRIMARY KEY,
            user_id VARCHAR(255) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB");
    }
}


function main()
{
    $existingVersion = getExistingVersion();

    runMigration(new Migration01, $existingVersion);
    runMigration(new Migration02, $existingVersion);

}

main();
