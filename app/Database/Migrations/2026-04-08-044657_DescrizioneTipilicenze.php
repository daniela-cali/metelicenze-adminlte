<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DescrizioneTipilicenze extends Migration
{
    public function up()
    {
        $fields = [
            'descrizione' => [
                'type' => 'TEXT',
                'null' => true,
            ]
        ];
        $this->forge->modifyColumn('tipilicenze', $fields);
    }

    public function down()
    {
        $fields = [
            'descrizione' => [
                'type' => 'varchar',
                'constraint' => '50',
                'null' => true,
            ]
        ];
        $this->forge->modifyColumn('tipilicenze', $fields);
    }
}
