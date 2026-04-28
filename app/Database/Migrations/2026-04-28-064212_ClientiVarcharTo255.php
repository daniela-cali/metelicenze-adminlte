<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClientiVarcharTo255 extends Migration
{
    public function up()
    {
        $fields = [
            'indirizzo' => [
                'type' => 'varchar',
                'constraint' => 255
            ],
            'citta' => [
                'type' => 'varchar',
                'constraint' => 255
            ],
            'telefono' => [
                'type' => 'varchar',
                'constraint' => 255
            ],
            'email' => [
                'type' => 'varchar',
                'constraint' => 255
            ],
        ];
        $this->forge->modifyColumn('clienti', $fields);
    }

    public function down()
    {

    }
}
