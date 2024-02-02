<?php
spl_autoload_register( function($class) {
    $file = ROOT .'/'. strtolower(str_replace('\\','/', $class)) . '.php';
    if (file_exists($file)){
        require_once $file;
    }else{
        throw new Exception(sprintf('Class { %s } tidak ditemukan', $class));
    }
});

require_once APP . '/helper/public.php';

require_once VENDOR . '/autoload.php';