<?php

namespace App\Controllers;

use MA\PHPMVC\MVC\Controller;
use App\Controllers\Traits\UserServiceTrait;
use MA\PHPMVC\Exception\ValidationException;
use App\Models\User\UserProfileUpdateRequest;
use App\Models\User\UserPasswordUpdateRequest;

class ProfileController extends Controller
{
    use UserServiceTrait;

    protected $template = 'layouts/user';

    public function show() // Menampilkan profil pengguna
    {
        // implementation
    }

    public function edit() // Menampilkan formulir pengeditan profil
    {
        $user = $this->sessionService->current();

        return $this->view('profile/profile', [
            "title" => "Update user profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name
            ],
            'csrf_token' => set_CSRF('/user/profile')
        ]);
    }

    public function update() // Menyimpan perubahan pada profil yang telah diedit
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
            return $this->view('profile/profile', [
                "title" => "Update user profile",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $_POST['name']
                ]
            ]);
        }
    }

    public function changePassword() // Menampilkan formulir penggantian kata sandi
    {
        $user = $this->sessionService->current();
        return $this->view('profile/password', [
            "title" => "Update user password",
            "user" => [
                "id" => $user->id
            ],
            'csrf_token' => set_CSRF('/user/password')
        ]);
    }

    public function updatePassword() // Menyimpan perubahan pada kata sandi
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
            return $this->view('User/password', [
                "title" => "Update user password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id
                ]
            ]);
        }
    }
}