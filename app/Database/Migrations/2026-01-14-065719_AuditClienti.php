<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AuditClienti extends Migration
{
    public function up()
    {
        $fields = [
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
        ];
        $this->forge->addColumn('clienti', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('clienti', ['created_by', 'updated_by', 'deleted_at', 'deleted_by']);
    }
}
