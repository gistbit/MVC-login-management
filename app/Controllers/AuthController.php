<?php

namespace App\Controllers;

use MA\PHPMVC\MVC\Controller;
use MA\PHPMVC\Interfaces\Request;
use App\Models\User\UserLoginRequest;
use App\Models\User\UserRegisterRequest;
use App\Controllers\Traits\UserServiceTrait;
use MA\PHPMVC\Exception\ValidationException;

class AuthController extends Controller
{
    use UserServiceTrait;

    protected $template = 'layouts/app';

    public function showLogin() // Menampilkan formulir login
    {
        response()->setNoCache();
        return $this->view('auth/login', [
            'title' => 'Login User',
            'csrf_token' => set_CSRF('/user/login')
        ]);
    }

    public function login(Request $request) // Proses login pengguna
    {
        $login = new UserLoginRequest();
        $login->id = $request->post('id');
        $login->password = $request->post('password');

        try {
            $response = $this->userService->login($login);
            $this->sessionService->create($response->user);
            response()->redirect('/');
        } catch (ValidationException $exception) {

            return $this->view('auth/login', [
                'title' => 'Login User',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function showRegistration() // Menampilkan formulir registrasi
    {
        return $this->view('auth/register', [
            'title' => 'Register New User',
            'csrf_token' => set_CSRF('/user/register')
        ]);
    }

    public function register(Request $request)  // Proses registrasi pengguna
    {
        $register = new UserRegisterRequest();
        $register->id = $request->post('id');
        $register->name = $request->post('name');
        $register->password = $request->post('password');

        try {
            $this->userService->register($register);
            response()->redirect('/user/login');
        } catch (ValidationException $exception) {

            return $this->view('auth/register', [
                'title' => 'Register new User',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function showResetPassword() // Menampilkan formulir reset password
    {
        // Implementation
    }

    public function resetPassword() // Proses reset password
    {
        // Implementation
    }

    public function logout() // Proses logout pengguna
    {
        $this->sessionService->destroy();
        response()->redirect('/');
    }
}