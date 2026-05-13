<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'prenom' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'TEXT',
                'null'       => true,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'role' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'departement_id' => [
                'type'       => 'INTEGER',
                'null'       => true,
            ],
            'date_embauche' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'actif' => [
                'type'       => 'INTEGER',
                'default'    => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('departement_id', 'departements', 'id');
        $this->forge->createTable('employes');
    }

    public function down()
    {
        $this->forge->dropTable('employes');
    }
}