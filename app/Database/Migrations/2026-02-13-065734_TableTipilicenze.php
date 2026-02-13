<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TableTipilicenze extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'descrizione' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'categoria' => [
                'type' => 'enum',
                'constraint' => ['gest_contab', 'fatt_elett', 'firma_digitale'],
                'null' => false,
                'default' => 'gest_contab',
            ],
            'stato' => [
                'type' => 'boolean',
                'default' => true,
                'null' => false,
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'created_at',
            ],
            'updated_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'updated_at',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_by',
            ],
            'deleted_by' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'deleted_at',
            ],

            // Timestamps
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ];


        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tipilicenze');
        $this->forge->addPrimaryKey('id', 'pk_tipilicenze');
    }

    public function down()
    {
        $this->forge->dropTable('tipilicenze', true);
    }
}
