<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
/**
 * Questa migrazione cambia la logica di gestione dei tipi di licenza:
 * Prima: la tabella "tipilicenze" prevedeva l'associazione a pià fornitori,
 * ma di fatto i prodotti sono sempre gli stessi, cambiano eventualmente le categorie dei prodotti (gestite al momento con enum, non è necessaria una tabella a sé stante)
 * Quindi i prodotti saranno sempre gli stessi con diverse categorie.
 * Aggiungo anche il tipo in modo da rendere il modello un nuovo enum in questa tabella, togliendolo poi dalla tabella licenze in modo da lasciarla pulita.
 * Nome sostituisce licenze.tipo,
 * Modello viene aggiunto e riporterà lo stesso enum di licenze.modello,
 * Aggiungo anche fornitori_id come chiave esterna (1 prodotto -> 1 fornitore, 1 fornitore -> più prodotti).
 * Verrà poi droppata la tabella pivot non più utile dopo il cambio di logica e l'aggiunta di fornitori_id
 */
class AlterTipiLicenze extends Migration
{
    public function up()
    {
        $newFields = [
            'modello' => [
                'type'       => "ENUM('Start','Ultimate','Cloud','Unico')",
                'default'    => 'Unico',
                'after'      => 'descrizione',
            ],
            'fornitori_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'=> true,
                'after' => 'categoria',
            ],
        ];
        $this->forge->addColumn('tipilicenze', $newFields);
        $this->forge->addForeignKey('fornitori_id', 'fornitori', 'id', 'CASCADE', 'CASCADE', 'fk_tipilicenze_fornitori');
        $this->forge->processIndexes('tipilicenze');
        $this->forge->dropTable('fornitori_tipilicenze_map', true);
    }

    public function down()
    {
        /**NOTA
         * la tabella fornitori_tipilicenze_map non viene ricreata nel rollback.
         *  In caso di down(), ripristinare manualmente dal backup del database.
         */
        $newFields = [
            'modello' => [
                'type'       => "ENUM('Start','Ultimate','Cloud','Unico')",
                'default'    => 'Unico',
            ],
            'fornitori_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ];
        $this->forge->dropForeignKey('tipilicenze', 'fk_tipilicenze_fornitori');
        $this->forge->dropColumn('tipilicenze', array_keys($newFields));
    }
}
