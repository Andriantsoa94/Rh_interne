<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConges extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'employe_id' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'type_conge_id' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'date_debut' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'date_fin' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'motif' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'statut' => [
                'type'       => 'TEXT',
                'default'    => 'en_attente',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employe_id', 'employes', 'id');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id');
        $this->forge->createTable('conges');
    }

    public function down()
    {
        $this->forge->dropTable('conges');
    }
}