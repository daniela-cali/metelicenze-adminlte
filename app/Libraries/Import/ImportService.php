<?php

namespace App\Libraries\Import;
use App\Models\Import\TranscodificheModel;
use App\Models\Admin\DatabaseInfoModel;


class ImportService
{
    protected $trancodificheModel;

    public function __construct()
    {
        $this->trancodificheModel = new TranscodificheModel();
       
    }

    public function loadTablesFields(): string
    {
        // usa DatabaseInfoModel per leggere i campi della tabella
        // fa upsert su transcodifiche con tabella_dest e campo_dest
        // lascia tabella_ori e campo_ori vuoti, li mapperà l'utente dopo
        $db = db_connect();
        $dbInfoModel = new DatabaseInfoModel();
        $tables = $dbInfoModel->getTables($db);
        //Elenco le tabelle autorizzate all'importazione
        $allowedTables = ['clienti', 'fornitori'];
        
        if(is_array($tables) && count($tables)>=1) {
            //Ciclo tutte le tabelle e per ognuna estraggo i campi
            foreach ($tables as $table) {
                $tableName = $table["tablename"];
                if (!in_array($tableName, $allowedTables)) continue;

                $fields[$tableName] = $dbInfoModel->getTableFields($db, $tableName);
                foreach ($fields[$tableName] as $field) {
                    $data[] = [
                        'tabella_dest' => $tableName,
                        'campo_dest' => $field["column_name"],
                        'tabella_ori' => null,
                        'campo_ori' => null,
                    ];
                }
            }
        }
        $this->trancodificheModel->upsertBatch($data);
        return "Transcodifiche caricate correttamente";

    }

    public function import(string $table, string $source, string $path = 'null'): string
    {
        return match ($source) {
            'database' => $this->importFromDatabase($table),
            'csv'      => $this->importFromCsv($table, $path),
            default    => throw new \Exception("Source '$source' not supported"),
        };
    }

    private function importFromDatabase(string $table): string {}

    private function importFromCsv(string $table, $path): string {}
}
