<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExtFornitori extends Migration
{
public function up()
    {
        $fields = [
            'id_external' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'ID esterno per integrazione con sistemi terzi',
                'after' => 'id',
            ],
            'codice_external' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'codice',
                'comment' => 'Codice esterno per integrazione con sistemi terzi',
            ],
            
        ];
        $this->forge->addColumn('fornitori', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('fornitori', ['id_external', 'codice_external']);
    }
}
