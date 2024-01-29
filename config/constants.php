<?php
// path root
define('ROOT', str_replace('\\', '/', rtrim(dirname(__DIR__), '/')));
define('APP', ROOT . '/App');
define('CONTROLLERS', APP . '/Controllers');
define('MODELS', APP . '/Models');
define('VIEWS', APP . '/views');
define('UPLOAD', ROOT . '/public/upload');
define('CONFIG', ROOT . '/config');
define('DOC_ROOT', str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')));
define('ROUTER', ROOT . '/router');
define('VENDOR', ROOT . '/vendor');

// base URL
define('BASE_URL', "http://www.localhost:8080");
// URL sumber menggunakan base URL
define('UPLOAD_URL', BASE_URL . '/upload');