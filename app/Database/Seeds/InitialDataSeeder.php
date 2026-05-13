<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Insert admin
        $this->db->table('employes')->insert([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'role' => 'admin',
            'departement_id' => null,
            'date_embauche' => date('Y-m-d'),
            'actif' => 1,
        ]);

        // Insert employees
        $this->db->table('employes')->insertBatch([
            [
                'nom' => 'Doe',
                'prenom' => 'John',
                'email' => 'john.doe@example.com',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role' => 'employee',
                'departement_id' => 1,
                'date_embauche' => date('Y-m-d'),
                'actif' => 1,
            ],
            [
                'nom' => 'Smith',
                'prenom' => 'Jane',
                'email' => 'jane.smith@example.com',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
                'role' => 'employee',
                'departement_id' => 1,
                'date_embauche' => date('Y-m-d'),
                'actif' => 1,
            ],
        ]);

        // Insert leave types
        $this->db->table('types_conge')->insertBatch([
            [
                'libelle' => 'Congé annuel',
                'jours_annuels' => 30,
                'deductible' => 1,
            ],
            [
                'libelle' => 'Congé maladie',
                'jours_annuels' => 15,
                'deductible' => 0,
            ],
            [
                'libelle' => 'Congé maternité',
                'jours_annuels' => 90,
                'deductible' => 0,
            ],
        ]);

        // Insert initial balances
        $this->db->table('soldes')->insertBatch([
            [
                'employe_id' => 1,
                'type_conge_id' => 1,
                'annee' => date('Y'),
                'jours_attribues' => 30,
                'jours_pris' => 0,
            ],
            [
                'employe_id' => 2,
                'type_conge_id' => 1,
                'annee' => date('Y'),
                'jours_attribues' => 30,
                'jours_pris' => 0,
            ],
            [
                'employe_id' => 3,
                'type_conge_id' => 1,
                'annee' => date('Y'),
                'jours_attribues' => 30,
                'jours_pris' => 0,
            ],
        ]);
    }
}