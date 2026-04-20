<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameTplicenzeIdTipilicenzeId extends Migration
{
    public function up()
    {
        $fields = [
            'tplicenze_id' => [
                'name' => 'tipilicenze_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ];
        $this->forge->modifyColumn('licenze', $fields);
        // ON UPDATE CASCADE: se l'id del tipo cambia, si aggiorna automaticamente
        // ON DELETE SET NULL: se si elimina un tipo licenza, le licenze collegate restano ma perdono il riferimento
        $this->forge->addForeignKey('tipilicenze_id', 'tipilicenze', 'id', 'CASCADE', 'SET NULL', 'fk_licenze_tipilicenze');
        $this->forge->processIndexes('licenze');
    }

    public function down()
    {
        $fields = [
            'tipilicenze_id' => [
                'name' => 'tplicenze_id',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ];
        $this->forge->modifyColumn('licenze', $fields);
        $this->forge->dropForeignKey('licenze', 'fk_licenze_tipilicenze');

    }
}
