<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): \CodeIgniter\HTTP\RedirectResponse
    {
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            switch ($role) {
                case 'admin':
                    return redirect()->to('/admin');
                case 'rh':
                    return redirect()->to('/rh');
                case 'employe':
                    return redirect()->to('/employe');
                default:
                    return redirect()->to('/login');
            }
        }

        return redirect()->to('/login');
    }
}
