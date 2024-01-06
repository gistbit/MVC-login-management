<?php
namespace app\DTOs;

class UserLoginRequest{
    public ?string $id = null;
    public ?string $password = null;
}