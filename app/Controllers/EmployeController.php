<?php

namespace App\Controllers;

class EmployeController extends BaseController
{
    public function dashboard(): string
    {
        return 'Employe dashboard';
    }

    public function index(): string
    {
        return 'Employe demandes';
    }

    public function create(): string
    {
        return 'Employe create';
    }

    public function profil(): string
    {
        return 'Employe profil';
    }
}
