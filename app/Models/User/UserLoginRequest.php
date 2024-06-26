<?php

namespace App\Models\User;

use MA\PHPMVC\MVC\Model;

class UserLoginRequest extends Model
{
    public ?string $id = null;
    public ?string $password = null;

    public function rules(): array
    {
        return [
            'id' => 'required',
            'password' => 'required'
        ];
    }

}
