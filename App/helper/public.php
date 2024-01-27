<?php
namespace App\helper;

function cetak($arr){
    echo '<pre>';
        print_r($arr);
    echo '</pre>';
}

function response(){
  return $GLOBALS['response'];
}

function request(){
  return $GLOBALS['request'];
}