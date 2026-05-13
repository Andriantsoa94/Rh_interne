<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldes extends Migration
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
            'annee' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'jours_attribues' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'jours_pris' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('employe_id', 'employes', 'id');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id');
        $this->forge->createTable('soldes');
    }

    public function down()
    {
        $this->forge->dropTable('soldes');
    }
}