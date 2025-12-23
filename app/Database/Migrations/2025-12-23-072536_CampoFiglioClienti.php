<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CampoFiglioClienti extends Migration
{
    public function up()
    {
        $fields = [

            'figlio_sn' => [
                'type' => 'boolean',
                'default' => false,
                'null' => false,
            ]
        ];
        $this->forge->addColumn('clienti', $fields);
    }

    public function down()
    {
     $this->forge->dropColumn('clienti', ['figlio_sn']);
    }
}
