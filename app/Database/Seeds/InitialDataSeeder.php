<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Insert departements
        $this->db->table('departements')->insert([
            'nom' => 'Ressources Humaines',
        ]);

        // Insert admin
        $this->db->table('employes')->insert([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'role' => 'admin',
            'departement_id' => null,
            'date_embauche' => date('Y-m-d'),
            'actif' => 1,
        ]);
    }
}