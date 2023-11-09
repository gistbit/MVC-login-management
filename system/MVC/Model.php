<?php

namespace MVC;

abstract class Model {

    abstract protected function getData();

    public function __construct() {
        \Database\Database::getConnection();
    }

}
