<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ImportFieldsFornitori extends Migration
{
    public function up()
    {
        $fields = [
            'dt_import' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Data dell\'ultimo import',
            ],
            'utente_import' => [
                'type'       => 'integer',
                'null'       => true,
                'default'    => null,
            ]
        ];
        $this->forge->addColumn('fornitori', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('fornitori', ['dt_import', 'utente_import']);
    }
}
