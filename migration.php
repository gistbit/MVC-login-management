<?php

require_once __DIR__ . '/vendor/autoload.php';

use MA\PHPMVC\Database\Database;

try {
    $dbh = Database::getConnection();
    
    // Buat tabel users
    $dbh->exec("CREATE TABLE IF NOT EXISTS users (
        id VARCHAR(255) PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role INT NOT NULL
    ) ENGINE=InnoDB");
    
    echo "Tabel users berhasil dibuat atau sudah ada.\n";
    
    // Buat tabel sessions
    $dbh->exec("CREATE TABLE IF NOT EXISTS sessions (
        id VARCHAR(255) PRIMARY KEY,
        user_id VARCHAR(255) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    ) ENGINE=InnoDB");
    
    echo "Tabel sessions berhasil dibuat atau sudah ada.\n";

} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
