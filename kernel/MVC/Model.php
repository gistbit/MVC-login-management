<?php

namespace MA\PHPMVC\MVC;
use MA\PHPMVC\Database\Database;

abstract class Model {

    protected \PDO $connection;

    public function __construct() {
       $this->connection = Database::getConnection();
    }

}