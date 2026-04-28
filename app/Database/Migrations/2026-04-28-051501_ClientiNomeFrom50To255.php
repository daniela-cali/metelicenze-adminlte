<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClientiNomeFrom50To255 extends Migration
{
    public function up()
    {
        $fields = [
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,

            ],
        ];
        $this->forge->modifyColumn('clienti', $fields);
    }

    public function down()
    {
        $fields = [
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,

            ],
        ];
        $this->forge->modifyColumn('clienti', $fields);
    }
}
