<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login(): string
    {
        return 'Login page';
    }

    public function attempt(): string
    {
        return 'Login attempt';
    }

    public function logout(): string
    {
        return 'Logout';
    }
}
