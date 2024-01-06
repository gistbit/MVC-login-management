<?php

namespace App\Models;

class HomeModel extends \App\Core\MVC\Model {

    public function getData() {
       
        return [
            'title'      => 'PHP LOGIN-MANAJEMENT',
            'nama'      =>  'Muhammad Akram'
        ];
    }
}
