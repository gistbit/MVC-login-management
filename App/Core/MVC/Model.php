<?php

namespace App\Core\MVC;
use App\Core\Database\Database;

abstract class Model {

    protected $connection;

    public function __construct() {
       $this->connection = Database::getConnection();
    }

}
