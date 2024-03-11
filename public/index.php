<?php

// Include the autoloader to load necessary classes
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Import the Router class from the specified namespace
use MA\PHPMVC\Router\Router;

// Load the application by including the bootstrap.php file
// Note: The bootstrap.php file is expected to return an instance of the application
$app = require_once dirname(__DIR__) . '/bootstrap.php';

// Run the application with the provided Router instance
$response = $app->run(new Router());

// Send the response, which typically involves outputting content to the user
$response->send();
