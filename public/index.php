<?php

// Include the autoloader to load necessary classes
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load the application by including the bootstrap.php file
// Note: The bootstrap.php file is expected to return an instance of the application
$app = require_once dirname(__DIR__) . '/bootstrap.php';

// Run the application
$response = $app->run();

// Send the response, which typically involves outputting content to the user
$response->send();
