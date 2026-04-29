<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Popola licenze.tipilicenze_id leggendo i valori ancora presenti in licenze.tipo e licenze.modello.
 *
 * Mapping usato (basato sui record correnti di tipilicenze):
 *   Sigla  + modello Start    → tipilicenze.id 1
 *   Sigla  + modello Ultimate → tipilicenze.id 2
 *   Sigla  + modello Cloud    → tipilicenze.id 3
 *   VarHub (qualsiasi modello)→ tipilicenze.id 4
 *   SKNT   (qualsiasi modello)→ tipilicenze.id 5 (SKNT Signer)
 *
 * La JOIN su nome+modello rende la migration robusta rispetto a eventuali
 * riassegnazioni degli ID in tipilicenze: funziona anche se i numeri cambiano,
 * purché nome e modello corrispondano.
 *
 * Precondizione: tipilicenze è già popolata con i record corretti.
 * Il campo tipilicenze_id in licenze è nullable; le righe già valorizzate
 * non vengono toccate (WHERE l.tipilicenze_id IS NULL).
 */
class PopolaLicenzeTipilicenzeId extends Migration
{
    public function up()
    {
        // Sigla: tre varianti identificate dal modello
        $this->db->query("
            UPDATE licenze l
            JOIN tipilicenze t ON (t.nome = 'Sigla' AND t.modello = l.modello)
            SET l.tipilicenze_id = t.id
            WHERE l.tipo = 'Sigla'
              AND l.tipilicenze_id IS NULL
        ");

        // VarHub: un unico record in tipilicenze, modello non discriminante
        $this->db->query("
            UPDATE licenze l
            JOIN tipilicenze t ON (t.nome = 'VarHub')
            SET l.tipilicenze_id = t.id
            WHERE l.tipo = 'VarHub'
              AND l.tipilicenze_id IS NULL
        ");

        // SKNT: mappa su 'SKNT Signer'
        $this->db->query("
            UPDATE licenze l
            JOIN tipilicenze t ON (t.nome = 'SKNT Signer')
            SET l.tipilicenze_id = t.id
            WHERE l.tipo = 'SKNT'
              AND l.tipilicenze_id IS NULL
        ");
    }

    public function down()
    {
        // Azzera il campo; i vecchi valori tipo/modello sono ancora presenti
        // e possono essere usati per ripopolare se serve.
        $this->db->query("UPDATE licenze SET tipilicenze_id = NULL");
    }
}