<?php

use MVC\Model;

class HomeModel extends Model {

    public function getData() {
        // can you connect to database
        // $this->db->query( write your sql syntax: "SELECT * FROM " . DB_PREFIX . "user");

        return [ 
            'title'      => 'PHP LOGIN-MANAJEMENT',
            'nama'      =>  'Muhammad Akram'
        ];
    }
}
