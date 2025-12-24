<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PadreId extends Migration
{
    public function up()
    {
        $fields = [

            'padre_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'ID del cliente padre se il cliente è un figlio',
            ]
        ];
        $this->forge->addColumn('clienti', $fields);
    }

    public function down()
    {
       $this->forge->dropColumn('clienti', ['padre_id']);
    }
}
