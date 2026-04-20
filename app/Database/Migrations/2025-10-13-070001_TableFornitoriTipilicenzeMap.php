<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TableFornitoriTipilicenzeMap extends Migration
{
    public function up()
    {
        $fields = [
            'fornitori_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
            ],
            'tipilicenze_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey(['fornitori_id', 'tipilicenze_id']);
        $this->forge->addKey('fornitori_id');
        $this->forge->addKey('tipilicenze_id');
        $this->forge->addForeignKey('fornitori_id',   'fornitori',   'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tipilicenze_id', 'tipilicenze', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('fornitori_tipilicenze_map', true);
    }

    public function down()
    {
        $this->forge->dropTable('fornitori_tipilicenze_map', true);
    }
}
