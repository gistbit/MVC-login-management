<?php

namespace App\Core\MVC;
use App\Core\Database\Database;

class Model {

    protected $connection;

    public function __construct() {
       $this->connection = Database::getConnection();
    }

}
