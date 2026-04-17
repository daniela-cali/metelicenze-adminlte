<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TableTipilicenze extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'descrizione' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'categoria' => [
                'type'       => 'ENUM',
                'constraint' => ['gest_contab', 'fatt_elett', 'firma_digitale'],
                'null'       => false,
                'default'    => 'gest_contab',
            ],
            'stato' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
            'created_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'updated_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tipilicenze');
    }

    public function down()
    {
        $this->forge->dropTable('tipilicenze', true);
    }
}
