<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $model = new EmployeModel();
            $employe = $model->getEmployeByEmail($email);

            if ($employe && password_verify($password, $employe['password'])) {
                $session = session();
                $sessionData = [
                    'id'         => $employe['id'],
                    'nom'        => $employe['prenom'] . ' ' . $employe['nom'],
                    'email'      => $employe['email'],
                    'role'       => $employe['role'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                // Redirection en fonction du rôle
                switch ($employe['role']) {
                    case 'admin':
                        return redirect()->to('/admin');
                    case 'rh':
                        return redirect()->to('/rh');
                    default:
                        return redirect()->to('/employe');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
            }
        }

        return view('login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
