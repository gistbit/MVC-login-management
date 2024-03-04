<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use MA\PHPMVC\Core\Router\Router;

// Load the application
$app = require_once APP . '/app.php';

// Run the application
$app->run(new Router())->render();