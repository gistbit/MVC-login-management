<?php

namespace App\Middleware;

class Auth {
   private $admin = false;
   
   public static function admin() {
       $roleObject = new self();
       $roleObject->admin = true;
       return $roleObject;
   }

   public function isAdmin() {
       return $this->admin;
   }
}