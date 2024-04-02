<?php

use MA\PHPMVC\Utility\Config;
use MA\PHPMVC\Http\Request;
use MA\PHPMVC\Http\Response;
use MA\PHPMVC\Router\Router;

// Path constants
define('DIR_ROOT', str_replace('\\', '/', __DIR__));
define('CONFIG', DIR_ROOT . '/config');
define('VIEWS', DIR_ROOT . '/app/views');
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
