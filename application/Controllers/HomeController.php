<?php
use MVC\Controller;
use Database\Database;

class HomeController extends Controller {

    private SessionService $sessionService;

    private $user;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
        parent::__construct();

    }

    public function index() {
        $this->user = $this->sessionService->current();
        if($this->user == null){
            $model = $this->model('HomeModel');
            $data = $model->getData();
            $html = $this->view->renderView('home/index');
            $this->response->setContent($html);
        }else{
            $this->dashboard();            
        }        
    }
    private function dashboard() {
        $model = $this->model('HomeModel');
        $data = $model->getData();
        $html = $this->view->renderView('home/dashboard', [
            "title" => "Dashboard",
            "user" => [
                "name" => $this->user->name
            ]
        ]);
        $this->response->setContent($html);
    }


    private function saveDataToDatabase($name) {
        $db = Database::getConnection();
        $name = $db->escape($name);
    
        // Menggunakan prepared statement untuk menghindari SQL Injection
        $sql = "INSERT INTO users (name) VALUES (:name)";
        $params = array(':name' => $name);
    
        return $db->query($sql, $params);
    }
    
}
