<?php

namespace App\Libraries\Import;

use App\Models\Import\TranscodificheModel;
use App\Models\Admin\DatabaseInfoModel;
use App\Models\Import\ClientiImportModel;
use CodeIgniter\Shield\Models\DatabaseException;

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

        if (is_array($tables) && count($tables) >= 1) {
            //Ciclo tutte le tabelle e per ognuna estraggo i campi
            foreach ($tables as $table) {
                $tableName = $table["tablename"];
                if (!in_array($tableName, $allowedTables)) continue;

                /*TODO: spostare $systemFields in Config\Import per condividerla con DatabaseInfoModel per pulizia dei campi impostati 
                * come interni in config e gestire anche la pagina nei settings
                * Verificare anche che il campo sia sparito da transcodifiche
                */
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
        return "Campi interni caricati con successo nella tabella!";
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

    private function importFromCsv(string $table, $path): string {
        //dd([$table, $path]);
        if (($handle = fopen($path, "r")) === FALSE) {
            throw new \Exception("Impossibile aprire il file CSV:". $path);
        }
        $campi_dest = $this->trancodificheModel->where('tabella_dest', $table)->findAll();
        //dd($campi_dest);
        $headers = fgetcsv($handle);
        $headersIndexes = array_flip($headers);
        //dd([$campi_dest,$headers,$headersIndexes]);
        $fields = [];
        $records = [];
        while(($row = fgetcsv($handle)) !== false) {
            //dd($row);
            foreach ($campi_dest as  $campo) {
                $colIndex = $headersIndexes[$campo["campo_ori"]];
                //dd([$colIndex,$campo['campo_dest'],$campo["campo_ori"], $headersIndexes]);
                $fields[$campo["campo_dest"]] = $row[$colIndex];
            }

            $records[] = $fields;
        }
        foreach ($records as &$record) {
            $record['dt_import'] = date('Y-m-d H:i:s');
            $record['utente_import'] = auth()->id();
            /* Casto i booleani in 0/1 per MySQL */
            $record['stato'] = filter_var($record['stato'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }
        //dd($records);
        $model = match($table) {
            'clienti' => new ClientiImportModel,
            default    => throw new \Exception("Tabella '$table' non supportata per l'import"),
        };
        $model->upsertBatch($records);
        return "Importazione completata: ". count($records) ." record elaborati";
    }

    public function getCsvFields(string $path, string $columnName = 'column_name')
    {
        //dd([$path,$columnName]);
        $fields = [];
        //Se non riesco ad aprire il file lancio un'eccezione 
        if (($handle = fopen($path, "r")) === FALSE) {
            throw new \Exception("Impossibile aprire il file CSV:". $path);
        }
        /* Prendo la prima riga del file e la associo a $headers */
        $headers =  fgetcsv($handle);
        /* Trova l'indice della colonna */
        $colIndex = array_search($columnName, $headers);
        if ($colIndex === false) {
            fclose($handle);
            throw new \Exception("Colonna '.$columnName.' non trovata nel CSV");
        } else {
            /* Cicla il CVS finché non fallisce (fine del file)*/
            while(($row = fgetcsv($handle)) !== false){
                $fields[] = $row[$colIndex];
            }
            fclose($handle);
            return $fields;
        }
    }
}
