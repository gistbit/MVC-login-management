<?php

use MA\PHPMVC\Utility\Config;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Router\Router;

// Path constants
define('ROOT', str_replace('\\', '/', rtrim(__DIR__ , '/')));
define('CONFIG', ROOT . '/config');
define('APP', ROOT . '/app');
define('CONTROLLERS', APP . '/Controllers');
define('MODELS', APP . '/Models');
define('VIEWS', APP . '/views');
define('DOC_ROOT', str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')));
define('UPLOAD', DOC_ROOT . '/upload');

// Base URL constants
define('BASE_URL', Config::get('app.url'));
define('UPLOAD_URL', BASE_URL . '/upload');

require_once CONFIG . '/routes.php';

return new Router(
    new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER),
    new Response()
);
