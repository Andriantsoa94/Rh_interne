<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard(): string
    {
        return 'Admin dashboard';
    }

    public function employes(): string
    {
        return 'Admin employes';
    }

    public function departements(): string
    {
        return 'Admin departements';
    }

    public function typesConges(): string
    {
        return 'Admin types conge';
    }

    public function soldes(): string
    {
        return 'Admin soldes';
    }
}
