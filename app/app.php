<?php

use MA\PHPMVC\Core\App;
use MA\PHPMVC\Core\Http\Request;
use MA\PHPMVC\Core\Http\Response;

// Initialize the application with dependency injection
$app = new App(
    new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER), 
    new Response()
);

return $app;