<?php

namespace App\Models\Admin;

use CodeIgniter\Model;


class DatabaseInfoModel extends Model
{

protected $dbConfig;

    public function __construct()
    {
        parent::__construct();
        $this->dbConfig = config('Database');
    }

    public function getDbInfo($db)
    {
        /**
         * Passo il db alla funzione e chiamo metodi privati a seconda del driver utilizzato
         */
        return match ($db->DBDriver) {
            'MySQLi' => $this->getDbInfoMysql($db),
            'Postgre' => $this->getDbInfoPostgre($db),
            default => throw new \Exception("Driver {$db->DBDriver} non supportato"),
        };
    }

    private function getDbInfoMysql($db)
    {
        return $db->query("
        SELECT 
            DATABASE() AS db_name,
            @@character_set_database AS encoding,
            @@collation_database AS collation,
            'N/A' AS ctype
    ")->getRowArray();
    }

    private function getDbInfoPostgre($db)
    {
        return $db->query("
        SELECT 
            current_database() AS db_name,
            pg_encoding_to_char(encoding) AS encoding,
            datcollate AS collation,
            datctype AS ctype
        FROM pg_database 
        WHERE datname = current_database()
    ")->getRowArray();
    }

    public function getTableFields($db, $table)
    {
        return match ($db->DBDriver) {
            'MySQLi' => $this->getTableFieldsMysql($db, $table),
            'Postgre' => $this->getTableFieldsPostgre($db, $table),
            default => throw new \Exception("Driver {$db->DBDriver} non supportato"),
        };   
    }

    private function getTableFieldsMysql($db, $table)
    {
        //$dbname = $this->dbConfig->{$db->DBGroup}['database'] // → "metelicenze" uso la forma più breve qui sotto
        /** In MySQL lo schema è solo il nome database in quanto non prevede l'utilizzo di schema diversi (schema = database)*/
        $dbName = $db->database;
        return $db->query("
        SELECT COLUMN_NAME as column_name,
               COLUMN_TYPE as data_type,
               IS_NULLABLE as is_nullable,
               COLUMN_DEFAULT as column_default
        FROM information_schema.columns
        WHERE table_name = ? AND table_schema = ?
        ORDER BY ORDINAL_POSITION
    ", [$table, $dbName])
    ->getResultArray();
    }

    private function getTableFieldsPostgre($db, $table)
    {
        /** In Postgre lo schema può cambiare, il db è solo un contenitore che può contenere più schema diversi 
         * e lo recupero dalla config istanziata nel costruttore
         **/
        
        $schema = $this->dbConfig->{$db->DBGroup}['schema'] ?? null;
        return $db->query("
        SELECT column_name, data_type, is_nullable, column_default
        FROM information_schema.columns
        WHERE table_name = ? AND table_schema = ?
        ORDER BY ordinal_position
    ", [$table, $schema])
    ->getResultArray();
    }
}
