<?php
use MVC\Controller;
use Database\Database;

class UserController extends Controller{

    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
        parent::__construct();

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);


    }

    public function register()
    {
        
        // $this->response->setHeader('Content-Type: application/json; charset=UTF-8');
        $html = $this->view->renderView('user/register', ['title'=> 'Register New User']);
        $this->response->setContent($html);
    }

    public function postRegister()
    {
     
        $request = new UserRegisterRequest();
        $request->id = $this->request->post('id');
        $request->name = $this->request->post('name');
        $request->password =$this->request->post('password');

        try { 
            $this->userService->register($request);
            $this->response->redirect('/user/login');
        } catch (ValidationException $exception) {
           
            $html = $this->view->renderView('user/register', [
                'title' => 'Register new User',
                'error' => $exception->getMessage()
            ]);
        $this->response->setContent($html);
        }
    }

    public function login()
    {
        // $this->response->setHeader('Content-Type: application/json; charset=UTF-8');
        $html = $this->view->renderView('user/login', ['title'=> 'Login User']);
        $this->response->setContent($html);
    }

    public function postLogin()
    {
     
        $request = new UserLoginRequest();
        $request->id = $this->request->post('id');
        $request->password = $this->request->post('password');

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            $this->response->redirect('/');
        } catch (ValidationException $exception) {
           
            $html = $this->view->renderView('user/login', [
                'title' => 'Login User',
                'error' => $exception->getMessage()
            ]);
        $this->response->setContent($html);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        $this->response->redirect('/');
    }


    public function updateProfile()
    {
        $user = $this->sessionService->current();

        $html = $this->view->renderView('user/profile', [
            "title" => "Update user profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);

        $this->response->setContent($html);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->name = $this->request->post('name');

        try {
            $this->userService->updateProfile($request);
            $this->response->redirect('/');
        } catch (ValidationException $exception) {
             $html = $this->view->renderView('user/profile', [
                "title" => "Update user profile",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name']
                ]
            ]);
            $this->response->setContent($html);
        }
    }

    public function updatePassword()
    {
        $user = $this->sessionService->current();
        $html = $this->view->renderView('user/password', [
            "title" => "Update user password",
            "user" => [
                "id" => $user->id
            ]
        ]);
        
        $this->response->setContent($html);
    }

    public function postUpdatePassword()
    {
        $user = $this->sessionService->current();
        $request = new UserPasswordUpdateRequest();
        $request->id = $user->id;
        $request->oldPassword = $this->request->post('oldPassword');
        $request->newPassword = $this->request->post('newPassword');

        try {
            $this->userService->updatePassword($request);
            $this->response->redirect('/');
        } catch (ValidationException $exception) {
            $html = $this->view->renderView('User/password', [
                "title" => "Update user password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id
                ]
            ]);
            
            $this->response->setContent($html);
        }
    }

}