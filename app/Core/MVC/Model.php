<?php

namespace App\Core\MVC;
use App\Core\Database\Database;
use Exception;

abstract class Model {

    protected $connection;

    /**
     * @throws Exception
     */
    public function __construct() {
       $this->connection = Database::getConnection();
    }

}
