<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExtFornitori extends Migration
{
public function up()
    {
        // id_external è già presente nella tabella base (TableFornitori migration).
        // Questa migration aggiunge solo codice_external, introdotto successivamente.
        $fields = [
            'codice_external' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'codice',
                'comment'  => 'Codice esterno per integrazione con sistemi terzi',
            ],
        ];
        $this->forge->addColumn('fornitori', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('fornitori', ['codice_external']);
    }
}
