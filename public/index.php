<?php

// Include the autoloader to load necessary classes
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Import the Router class from the specified namespace
use MA\PHPMVC\Core\Router\Router;

// Load the application by including the app.php file
// Note: The app.php file is expected to return an instance of the application
$app = require_once APP . '/app.php';

// Run the application with the provided Router instance
$response = $app->run(new Router());

// Render the response, which typically involves outputting content to the user
$response->render();
