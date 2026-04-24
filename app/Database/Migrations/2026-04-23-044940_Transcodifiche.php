<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transcodifiche extends Migration
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
            'tabella_dest' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
            'campo_dest' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
            'tabella_ori' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
            'campo_ori' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true,
            ],
        ];
        $this->forge->addField($fields);
        $this->forge->addKey('id', true); //Chiave primaria per operazioni CRUD
        $this->forge->addUniqueKey(['tabella_ori', 'campo_ori']); //Vincolo di univocità
        $this->forge->createTable('transcodifiche');
    }

    public function down()
    {
        $this->forge->dropTable('transcodifiche', true);
    }
}
