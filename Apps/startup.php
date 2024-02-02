<?php
spl_autoload_register( function($class) {
    $file = ROOT .'/'. str_replace(['App\\', '\\'], ['app/', '/'], $class) . '.php';
    if (file_exists($file)){
        require_once $file;
    }else{
        throw new Exception(sprintf('Class { %s } tidak ditemukan', $class));
    }
});

require_once APP . '/helper/public.php';

require_once VENDOR . '/autoload.php';