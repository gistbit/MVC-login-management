<?php

namespace MA\PHPMVC\Core\MVC;
use MA\PHPMVC\Core\Database\Database;

abstract class Model {

    protected \PDO $connection;

    public function __construct() {
       $this->connection = Database::getConnection();
    }

}