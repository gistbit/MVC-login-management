<?php

namespace MA\PHPMVC\Controllers;
use MA\PHPMVC\Core\Database\Database;
use MA\PHPMVC\Core\MVC\{Controller, View};
use MA\PHPMVC\Repository\{SessionRepository, UserRepository};
use MA\PHPMVC\Service\{SessionService, UserService};
use MA\PHPMVC\Exception\ValidationException;
use MA\PHPMVC\Models\{UserRegisterRequest, UserLoginRequest, UserProfileUpdateRequest, UserPasswordUpdateRequest};

class UserController extends Controller{

    private UserService $userService;
    private SessionService $sessionService;

    public function __construct()
    {
        parent::__construct();
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository);
    }

    public function register()
    {
        
        // $this->response->setHeader('Content-Type: application/json; charset=UTF-8');
        return View::renderView('user/register', ['title'=> 'Register New User']);
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
           
            return View::renderView('user/register', [
                'title' => 'Register new User',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function login()
    {
        return View::renderView('user/login', ['title'=> 'Login User']);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $this->request->post('id');
        $request->password = $this->request->post('password');

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user);
            $this->response->redirect('/');
        } catch (ValidationException $exception) {
           
            return View::renderView('user/login', [
                'title' => 'Login User',
                'error' => $exception->getMessage()
            ]);
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

        return View::renderView('user/profile', [
            "title" => "Update user profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ]
        ]);
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->name = $this->request->post('name');

        try {
            $response = $this->userService->updateProfile($request);
            $this->sessionService->create($response->user); //update cookie session setelah update profile
            $this->response->redirect('/');
        } catch (ValidationException $exception) {
             return View::renderView('user/profile', [
                "title" => "Update user profile",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name']
                ]
            ]);
        }
    }

    public function updatePassword()
    {
        $user = $this->sessionService->current();
        return View::renderView('user/password', [
            "title" => "Update user password",
            "user" => [
                "id" => $user->id
            ]
        ]);
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
            return View::renderView('User/password', [
                "title" => "Update user password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id
                ]
            ]);
        }
    }

}