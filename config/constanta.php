<?php

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('HTTP_URL', '/'. substr_replace(trim($_SERVER['REQUEST_URI'], '/'), '', 0, strlen($scriptName)));

// Define Path Application
define('ROOT', str_replace('\\', '/', rtrim(__DIR__, '/')) . '/../');
define('SYSTEM', ROOT . 'system/');
define('CONTROLLERS', ROOT . 'application/Controllers/');
define('MODELS', ROOT . 'application/Models/');
define('VIEWS', ROOT . 'application/Views/');
define('UPLOAD', ROOT . 'upload/');

define('APPLICATION', ROOT . 'application/');

