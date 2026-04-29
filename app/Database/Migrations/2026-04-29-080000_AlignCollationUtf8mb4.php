<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Allinea charset e collation di tutte le tabelle applicative a utf8mb4_unicode_ci,
 * che è il default di MySQL 8 e quello configurato in app/Config/Database.php.
 *
 * Le tabelle create in momenti diversi possono aver ereditato utf8mb4_general_ci
 * (vecchio default di CI4/MariaDB), causando "Illegal mix of collations" nei JOIN
 * tra tabelle con collation diverse.
 *
 * ALTER TABLE ... CONVERT TO CHARACTER SET converte anche tutte le colonne stringa
 * della tabella, non solo il default della tabella stessa.
 *
 * Le tabelle di Shield (auth_*) e le tabelle interne di CI4 (migrations) non
 * vengono toccate perché gestite dal framework.
 */
class AlignCollationUtf8mb4 extends Migration
{
    /** Tabelle applicative da allineare */
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
        $database = $this->db->getDatabase();
        $collation = 'utf8mb4_unicode_ci';
        $charset   = 'utf8mb4';

        // Imposta il default del database per le nuove tabelle create senza COLLATE esplicito
        $this->db->query("ALTER DATABASE `{$database}` CHARACTER SET {$charset} COLLATE {$collation}");

        // Converte ogni tabella applicativa e tutte le sue colonne stringa
        foreach ($this->tables as $table) {
            $this->db->query("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET {$charset} COLLATE {$collation}");
        }
    }

    public function down()
    {
        // Ripristina il vecchio default — le colonne tornano a general_ci
        $database = $this->db->getDatabase();
        $collation = 'utf8mb4_general_ci';
        $charset   = 'utf8mb4';

        $this->db->query("ALTER DATABASE `{$database}` CHARACTER SET {$charset} COLLATE {$collation}");

        foreach ($this->tables as $table) {
            $this->db->query("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET {$charset} COLLATE {$collation}");
        }
    }
}
