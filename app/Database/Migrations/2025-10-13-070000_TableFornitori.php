<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TableFornitori extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'codice' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => false,
                'comment'    => 'Stesso codice esterno per integrazione con sistemi terzi',
            ],
            'id_external' => [
                'type'       => 'INT',
                'null'       => true,
                'comment'    => 'ID esterno per integrazione con sistemi terzi',
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'piva' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
            ],
            'indirizzo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
            'citta' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => false,
            ],
            'cap' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => false,
            ],
            'provincia' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => false,
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'note' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Note aggiuntive sul fornitore',
            ],
            'contatti' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Elenco contatti associati al fornitore',
            ],
            'stato' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'null'    => false,
                'default' => 1,
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
        $this->forge->createTable('fornitori');
    }

    public function down()
    {
        $this->forge->dropTable('fornitori', true);
    }
}
