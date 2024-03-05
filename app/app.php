<?php

use MA\PHPMVC\Core\App;
use MA\PHPMVC\Core\Http\Request;

// Initialize the application with dependency injection
$app = new App(
    new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER)
);

return $app;