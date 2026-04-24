<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropCodiceExternalFornitori extends Migration
{
    public function up()
    {
        $fields=[
            'codice_external',
        ];
        $this->forge->dropColumn('fornitori',$fields);
    }

    public function down()
    {
        $fields = [
            'codice_external' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => null,
                'null'     => true,
                'comment'  => 'Codice esterno per integrazione con sistemi terzi',
            ]
        ];
        $this->forge->addColumn('fornitori',$fields);

    }
}
