<?php

namespace MA\PHPMVC\Core\MVC;
use MA\PHPMVC\Core\Database\Database;

abstract class Model {

    protected $connection;

    public function __construct() {
       $this->connection = Database::getConnection();
    }

}
