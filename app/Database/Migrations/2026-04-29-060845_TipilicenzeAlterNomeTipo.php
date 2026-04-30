<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TipilicenzeAlterNomeTipo extends Migration
{
    public function up()
    {
        $fields = [
            'nome' => [
                'name' => 'tipo',
                'type' => 'varchar',
                'constraint' => 64
            ]
        ];
        $this->forge->modifyColumn('tipilicenze', $fields);
    }

    public function down()
    {
        $fields = [
            'tipo' => [
                'name' => 'nome',
                'type' => 'varchar',
                'constraint' => 50
            ]
        ];
        $this->forge->modifyColumn('tipilicenze', $fields);
    }
}
