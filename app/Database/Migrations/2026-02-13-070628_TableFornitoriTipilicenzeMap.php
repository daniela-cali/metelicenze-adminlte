<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TableFornitoriTipilicenzeMap extends Migration
{
    public function up()
    {
        $fields = [
            'fornitori_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'unsigned'   => true,
            ],
            'tipilicenze_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
            ],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey(['fornitori_id', 'tipilicenze_id'], true); // Chiave primaria composta
        $this->forge->addKey('fornitori_id');
        $this->forge->addKey('tipilicenze_id');
        $this->forge->addForeignKey('fornitori_id', 'fornitori', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tipilicenze_id', 'tipilicenze', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('fornitori_tipilicenze_map');
    }

    public function down()
    {
        $this->forge->dropTable('fornitori_tipilicenze_map');
    }
}
