<?php

namespace App\Core\Http;
use App\Service\SessionService;
use Firebase\JWT\{JWT, Key};

class Request {


    public static $cookie;

    public $files;

   
    public function __construct() {
        self::$cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
    }

    public static function currentSession(): ?array
    {
        $JWT = self::$cookie[SessionService::COOKIE_NAME] ?? '';
        if (empty($JWT)) return null;
    
        try {
            $payload = JWT::decode($JWT, new Key(SessionService::KEY, 'HS256'));
            return (array)$payload;
        } catch (\Exception $e) {
            return null;
        }
    }
    

    public function get(String $key = '') {
        if ($key != '')
            return isset($_GET[$key]) ? $this->clean($_GET[$key]) : null;

        return $this->clean($_GET);
    }

  
    public function post(String $key = '') {
        if ($key != '')
            return isset($_POST[$key]) ? $this->clean($_POST[$key]) : null;

        return $this->clean($_POST);
    }

  
    public function input(String $key = '') {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata, true);

        if ($key != '') {
            return isset($request[$key]) ? $this->clean($request[$key]) : null;
        } 

        return ($request);
    }

    public function getPath() {
        return isset($_SERVER['PATH_INFO']) ? $this->clean($_SERVER['PATH_INFO']) : '/';
    }

    public function getMethod() {
        return isset($_SERVER['REQUEST_METHOD']) ? $this->clean($_SERVER['REQUEST_METHOD']) : 'GET';
    }
    
    private function clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {

                // Delete key
                unset($data[$key]);

                // Set new clean key
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }
}