<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Standardizza tutte le tabelle applicative a utf8mb4_unicode_ci.
 *
 * Necessaria perché la migration precedente (AlignCollationUtf8mb4) era stata
 * eseguita localmente con utf8mb4_0900_ai_ci (MySQL 8 only), collation non
 * supportata su MySQL 5.7 / MariaDB usato in produzione.
 *
 * utf8mb4_unicode_ci è compatibile con tutte le versioni e gestisce
 * correttamente le accentate italiane.
 *
 * Su produzione questa migration gira dopo AlignCollationUtf8mb4 (già corretta
 * a unicode_ci): l'operazione è idempotente, nessun danno.
 */
class AlignCollationUnicodeCI extends Migration
{
    private array $tables = [
        'licenze',
        'versioni',
        'clienti',
        'tipilicenze',
        'fornitori',
        'aggiornamenti',
        'transcodifiche',
    ];

    public function up()
    {
        $database  = $this->db->getDatabase();
        $charset   = 'utf8mb4';
        $collation = 'utf8mb4_unicode_ci';

        $this->db->query("ALTER DATABASE `{$database}` CHARACTER SET {$charset} COLLATE {$collation}");

        foreach ($this->tables as $table) {
            $this->db->query("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET {$charset} COLLATE {$collation}");
        }
    }

    public function down()
    {
        $database  = $this->db->getDatabase();
        $charset   = 'utf8mb4';
        $collation = 'utf8mb4_0900_ai_ci';

        $this->db->query("ALTER DATABASE `{$database}` CHARACTER SET {$charset} COLLATE {$collation}");

        foreach ($this->tables as $table) {
            $this->db->query("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET {$charset} COLLATE {$collation}");
        }
    }
}
