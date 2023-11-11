<?php

namespace MVC;

class Model {

    protected $connection;

    public function __construct() {
       $this->connection = \Database\Database::getConnection();
    }

}
