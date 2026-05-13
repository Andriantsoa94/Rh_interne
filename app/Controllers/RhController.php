<?php

namespace App\Controllers;

class RhController extends BaseController
{
    public function dashboard(): string
    {
        return 'RH dashboard';
    }

    public function index(): string
    {
        return 'RH demandes';
    }
}
