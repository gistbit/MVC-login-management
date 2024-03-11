<?php
// Path
define('ROOT', str_replace('\\', '/', rtrim(dirname(__DIR__), '/')));
define('APP', ROOT . '/app');
define('CONTROLLERS', APP . '/Controllers');
define('MODELS', APP . '/Models');
define('VIEWS', APP . '/views');
define('UPLOAD', ROOT . '/public/upload');
define('CONFIG', ROOT . '/config');
define('DOC_ROOT', str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')));
define('VENDOR', ROOT . '/vendor');

// Base URL
define('BASE_URL', "http://www.localhost:8080");
define('UPLOAD_URL', BASE_URL . '/upload');
