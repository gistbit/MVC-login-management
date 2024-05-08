<?php

// Include the autoloader to load necessary classes
require_once __DIR__ . '/../vendor/autoload.php';

// Load the router by including the bootstrap.php file
// The bootstrap.php file is expected to return an instance of the router
$router = require __DIR__ . '/../bootstrap.php';

// Run the router
$response = $router->run();

// Send the response, which typically involves outputting content to the user
$response->send();
