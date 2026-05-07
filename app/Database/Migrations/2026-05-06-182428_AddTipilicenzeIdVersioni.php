<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipilicenzeIdVersioni extends Migration
{
    public function up()
    {
        $fields = [
            'tipilicenze_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'comment'  => 'Riferimento a tipilicenze',
            ],
        ];
        $this->forge->addColumn('versioni', $fields);
        $this->db->query('ALTER TABLE `versioni` ADD CONSTRAINT `versioni_tipilicenze_fk` FOREIGN KEY (`tipilicenze_id`) REFERENCES `tipilicenze`(`id`) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `versioni` DROP FOREIGN KEY `versioni_tipilicenze_fk`');
        $this->forge->dropColumn('versioni', ['tipilicenze_id']);
    }
}
