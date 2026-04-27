<?php

namespace App\Models\Admin;

use CodeIgniter\Model;


class DatabaseInfoModel extends Model
{

    protected $dbConfig;

    protected $systemFields = [
        'id', 
        'dt_import', 
        'utente_import',
        'note',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'figlio_sn',
        'padre_id',
        ];

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

    public function getTables($db)
    {
        return match ($db->DBDriver) {
            'MySQLi' => $this->getTablesMysql($db),
            'Postgre' => $this->getTablesPostgre($db),
            default => throw new \Exception("Driver {$db->DBDriver} non supportato"),
        };
    }
    private function getTablesMysql($db)
    {
        $dbName = $db->database;
        return $db->query("
        SELECT table_name AS tablename
        FROM information_schema.tables
        WHERE table_schema = ?
        ORDER BY table_name
    ", [$dbName])->getResultArray();
    }

    private function getTablesPostgre($db)
    {
        $dbSchema = $db->schema;
        return $db->query("
        SELECT table_name AS tablename
        FROM information_schema.tables
        WHERE table_schema = ?
        ORDER BY table_name
    ", [$dbSchema])->getResultArray();
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
        /** In MySQL lo schema è solo il nome database in quanto non prevede l'utilizzo di schema diversi (schema = database)*/
        //$dbname = $this->dbConfig->{$db->DBGroup}['database'] // → "metelicenze" uso la forma più breve qui sotto
        $dbName = $db->database;
        return $db->table('information_schema.columns')
            ->select('COLUMN_NAME as column_name, COLUMN_TYPE as data_type, IS_NULLABLE as is_nullable, COLUMN_DEFAULT as column_default')
            ->where('table_name', $table)
            ->where('table_schema', $dbName)
            ->whereNotIn('COLUMN_NAME', $this->systemFields)
            ->orderBy('ORDINAL_POSITION')
            ->get()
            ->getResultArray();
    }

    private function getTableFieldsPostgre($db, $table)
    {
        /** In Postgre lo schema può cambiare, il db è solo un contenitore che può contenere più schema diversi
         * e lo recupero dalla config istanziata nel costruttore
         **/

        $schema = $this->dbConfig->{$db->DBGroup}['schema'] ?? null;
        return $db->table('information_schema.columns')
        ->select('COLUMN_NAME as column_name, COLUMN_TYPE as data_type, IS_NULLABLE as is_nullable, COLUMN_DEFAULT as column_default')
        ->where('table_name', $table)
        ->where('table_schema', $schema)
        ->notLike('COLUMN_NAME', '_id_pk', 'after')
        ->orderBy('ordinal_position')
        ->get()
        ->getResultArray();

    }
}
