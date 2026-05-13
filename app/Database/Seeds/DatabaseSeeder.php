<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Insert departements
        $this->db->query("INSERT INTO departements (nom) VALUES ('Ressources Humaines')");

        // Insert admin
        $this->db->query("INSERT INTO employes (nom, prenom, email, password, role, departement_id, date_embauche, actif) VALUES ('Admin', 'Super', 'admin@example.com', 'admin123', 'admin', NULL, '" . date('Y-m-d') . "', 1)");

        // Insert employees
        $this->db->query("INSERT INTO employes (nom, prenom, email, password, role, departement_id, date_embauche, actif) VALUES ('Doe', 'John', 'john.doe@example.com', 'password123', 'employee', 1, '" . date('Y-m-d') . "', 1)");
        $this->db->query("INSERT INTO employes (nom, prenom, email, password, role, departement_id, date_embauche, actif) VALUES ('Smith', 'Jane', 'jane.smith@example.com', 'password123', 'employee', 1, '" . date('Y-m-d') . "', 1)");

        // Insert leave types
        $this->db->query("INSERT INTO types_conge (libelle, jours_annuels, deductible) VALUES ('Congé annuel', 30, 1)");
        $this->db->query("INSERT INTO types_conge (libelle, jours_annuels, deductible) VALUES ('Congé maladie', 15, 0)");
        $this->db->query("INSERT INTO types_conge (libelle, jours_annuels, deductible) VALUES ('Congé maternité', 90, 0)");

        // Insert initial balances
        $this->db->query("INSERT INTO soldes (employe_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES (1, 1, " . date('Y') . ", 30, 0)");
        $this->db->query("INSERT INTO soldes (employe_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES (2, 1, " . date('Y') . ", 30, 0)");
        $this->db->query("INSERT INTO soldes (employe_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES (3, 1, " . date('Y') . ", 30, 0)");
    }
}