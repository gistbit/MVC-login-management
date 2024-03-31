<?php

namespace MA\PHPMVC\Exception;

class ValidationException extends \Exception
{
    private $errors;
    public function __construct($message = '', $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message);
    }

    public function getErr() {
        return $this->errors;
    }
}
