<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration creata il 20/04/2026.
 *
 * Svuota la tabella tipilicenze per permettere il reinserimento manuale dei dati
 * con i valori corretti per i nuovi campi modello e fornitori_id aggiunti dalla
 * migration AlterTipiLicenze (2026-04-20).
 *
 * Decisione: anziché correggere i dati esistenti tramite migration (come fatto
 * inizialmente in PopolaLicenzeTipilicenzeId), si preferisce partire da una
 * tabella pulita e reinserire i record a mano in DBeaver, in modo da avere
 * pieno controllo su nome, modello, fornitori_id e categoria di ogni tipo licenza.
 *
 * Nota: il TRUNCATE richiede di disabilitare temporaneamente i controlli FK
 * perché licenze.tipilicenze_id punta a questa tabella (fk_licenze_tipilicenze).
 * Al momento del truncate il campo è comunque tutto NULL, quindi nessun dato
 * viene perso su licenze.
 */
class TruncateTipilicenze extends Migration
{
    public function up()
    {
        // Disabilita i controlli FK per permettere il truncate su tabella referenziata
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->query('TRUNCATE TABLE tipilicenze');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down()
    {
        // Il rollback non è possibile: il TRUNCATE è irreversibile.
        // In caso di down(), ripristinare manualmente i dati dal backup del database.
    }
}
