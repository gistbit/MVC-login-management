<?php

namespace App\Controllers;

use MA\PHPMVC\MVC\Controller;
use App\Controllers\Traits\UserServiceTrait;
use MA\PHPMVC\Exception\ValidationException;
use App\Models\User\UserProfileUpdateRequest;
use App\Models\User\UserPasswordUpdateRequest;
use MA\PHPMVC\Interfaces\Request;

class ProfileController extends Controller
{
    use UserServiceTrait;

    protected $layout = 'app';

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

    public function update(Request $request) // Menyimpan perubahan pada profil yang telah diedit
    {
        $user = $this->sessionService->current();

        $profile = new UserProfileUpdateRequest();
        $profile->id = $user->id;
        $profile->name = $request->post('name');

        try {
            $response = $this->userService->updateProfile($profile);
            $this->sessionService->create($response->user); //update cookie session setelah update profile
            response()->redirect('/');
        } catch (ValidationException $exception) {
            return $this->view('profile/profile', [
                "title" => "Update user profile",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $request->post('name')
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

    public function updatePassword(Request $request) // Menyimpan perubahan pada kata sandi
    {
        $user = $this->sessionService->current();
        $password = new UserPasswordUpdateRequest();
        $password->id = $user->id;
        $password->oldPassword = $request->post('oldPassword');
        $password->newPassword = $request->post('newPassword');

        try {
            $this->userService->updatePassword($password);
            response()->redirect('/');
        } catch (ValidationException $exception) {
            return $this->view('profile/password', [
                "title" => "Update user password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id
                ]
            ]);
        }
    }
}