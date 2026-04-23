<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\DatabaseInfoModel;

helper('db_status');

class DatabaseInfoController extends BaseController
{
    public function connectionTest()
    {
        $database_config = config(\Config\Database::class);
        $profiles = get_object_vars($database_config);
        $databaseInfoModel = new DatabaseInfoModel();

        /* Analizzo il profili di connessione: se è un array con DBDriver, provo a testare la connessione (get_object_vars restituisce un array per tutte le proprietà delle classi, 
        quindi controllo che abbia DBDriver per essere sicura che sia un profilo di connessione valido) 
        Filtro per escludere properties non-array e config vuote*/
        $profiles = array_filter($profiles, function ($config) {
            return is_array($config) && !empty($config['DBDriver']);
        });

        $databases = [];
        $results = [];

        log_message('info', 'Profili trovati: ' . implode(', ', array_keys($profiles)));
        foreach ($profiles as $group => $config) {
            //log_message('info', 'Analizzando il profilo di connessione: ' . $group);
            if (db_is_available($group)) {
                //log_message('info', 'Database disponibile per il profilo: ' . $group);
                try { // Database disponibile controllando la connessione alla porta relativa (db_status helper) ed effettuo la connessione
                    $db = db_connect($group, false); //Attivo la connessione

                    $results[$group] = $databaseInfoModel->getDbInfo($db);
                    $databases[] = [
                        'title'            => 'Connessione Database ' . ucfirst($results[$group]['db_name'] ?? $group),
                        'db_name'          => $results[$group]['db_name'] ?? 'N/A',
                        'encoding'         => $results[$group]['encoding'] ?? 'N/A',
                        'collation'        => $results[$group]['collation'] ?? 'N/A',
                        'ctype'            => $results[$group]['ctype'] ?? 'N/A',
                        'driver'           => $db->DBDriver,
                        'hostname'         => $config['hostname'] ?? 'N/A',
                        'connection_group' => $group,
                        'status'           => 'Raggiungibile'
                    ];
                    log_message('info', 'Connessione testata con successo per il profilo: ' . $group);
                } catch (\Throwable $e) {
                    log_message('error', 'Errore durante l\'analisi del profilo di connessione: ' . $group . ' - ' . $e->getMessage());
                }
            } else {
                log_message('warning', 'Database non raggiungibile per il profilo: ' . $group);

                $databases[] = [
                    'title' => 'Connessione Database ' . ucfirst($profiles[$group]['database'] ?? $group),
                    'db_name' => $config['database'] ?? 'N/A',
                    'encoding' => 'N/A',
                    'collation' => 'N/A',
                    'ctype' => 'N/A',
                    'driver' => $config['DBDriver'] ?? 'N/A',
                    'hostname' => $config['hostname'] ?? 'N/A',
                    'connection_group' => $group,
                    'status' => 'Non Raggiungibile'
                ];
            }
        }

        return $this->view('admin/database/dbConnectionTest', [
            'databases' => $databases,
        ]);
    }




    public function getTableFields($db = 'default', $table = '')
    {
        log_message('info', 'Richiesta campi per la tabella: ' . $table . ' nel database: ' . $db);
        $databaseInfoModel = new DatabaseInfoModel();
        try {
            // Connessione diretta
            $db = db_connect($db, false);
            
            $result = $databaseInfoModel->getTableFields($db, $table);

            // Lista dei nomi dei campi
            $allowedFields = array_filter(
                array_column($result, 'column_name'),
                fn($field) => $field !== 'id'
            );

            return $this->view('admin/database/dbFields', [
                'fields'         => $result,
                'table_name'     => $table,
                'allowed_fields' => $allowedFields,
                'database'       => $db,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Errore nel recupero campi: ' . $e->getMessage());
            return $this->view('admin/database/dbFields', [
                'error'      => $e->getMessage(),
                'table_name' => $table,
                'database'   => $db,
            ]);
        }
    }


    public function showLog()
    {
        log_message('info', 'Richiesta visualizzazione log per il database');
        // Percorso del file di log
        $default = db_connect('default', false);
        $defaultDriver = $default->DBDriver;

        $folder_default = $default->query("SHOW VARIABLES LIKE 'datadir';");
        $log_file_default = $default->query("SHOW VARIABLES where variable_name = 'log_error'");
        echo 'Folder default: ' . $folder_default->getRow()->Value . '<br>';
        echo 'Log file default: ' . $log_file_default->getRow()->Value . '<br>';
        $path_default = $folder_default->getRow()->Value . $log_file_default->getRow()->Value;
        log_message('info', 'Percorso del log per il database default: ' . $path_default);
        echo $path_default;
        die();

        $external = db_connect('external', false);

        $file = WRITEPATH . 'logs/db_log.txt'; // oppure dove tieni il file
        $lines = [];

        if (is_file($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES);
        }

        return $this->view('log_database', [
            'file_name' => basename($file),
            'log_lines' => $lines
        ]);
    }


    public function info($connectionGroup = 'default')
    {
        log_message('info', 'Richiesta informazioni per il database: ' . $connectionGroup);

        try {
            // Connessione al database
            $db = db_connect($connectionGroup, false);
            $config = config('Database');
            $schema = $config->{$connectionGroup}['schema'] ?? null;

            // Driver del database
            $driver = $db->DBDriver;
            log_message('info', 'Schema utilizzato: ' . $schema);
            log_message('info', 'Driver del database: ' . $driver);

            switch ($driver) {
                case 'MySQLi':
                    log_message('info', 'Driver MySQLi rilevato');

                    $dbInfoRaw = $db->query("
                    SELECT 
                        DATABASE() AS db_name,
                        @@character_set_database AS charset,
                        @@collation_database AS collation
                ")->getRow();

                    // Normalizzo le proprietà per la view
                    $dbInfo = [
                        'db_name'   => $dbInfoRaw->db_name,
                        'encoding'  => $dbInfoRaw->charset,
                        'collation' => $dbInfoRaw->collation,
                        'ctype'     => null
                    ];

                    // Tabelle dello schema
                    $tables = $db->query("
                    SELECT table_name AS tablename
                    FROM information_schema.tables
                    WHERE table_schema = ?
                    ORDER BY table_name
                ", [$dbInfo['db_name']])->getResultArray();
                    break;

                case 'Postgre':
                    log_message('info', 'Driver PostgreSQL rilevato');

                    // Informazioni sul database
                    $dbInfoRaw = $db->query("
                    SELECT 
                        current_database() AS db_name,
                        pg_encoding_to_char(encoding) AS encoding,
                        datcollate AS collation,
                        datctype AS ctype
                    FROM pg_database 
                    WHERE datname = current_database()
                ")->getRow();

                    // Normalizzo le proprietà per la view
                    $dbInfo = [
                        'db_name'   => $dbInfoRaw->db_name,
                        'encoding'  => $dbInfoRaw->encoding,
                        'collation' => $dbInfoRaw->collation,
                        'ctype'     => $dbInfoRaw->ctype
                    ];

                    // Tabelle dello schema
                    $tables = $db->query("
                    SELECT tablename 
                    FROM pg_tables 
                    WHERE schemaname = ? 
                    ORDER BY tablename
                ", [$schema])->getResultArray();
                    break;

                default:
                    throw new \Exception("Driver $driver non supportato");
            }

            $data = [
                'dbInfo'   => $dbInfo,
                'schema'   => $schema,
                'tables'   => $tables,
                'title'    => 'Informazioni Database',
                'database' => $connectionGroup,
            ];

            return $this->view('admin/database/dbInfo', $data);
        } catch (\Exception $e) {
            return $this->view('admin/database/dbInfo', ['error' => $e->getMessage()]);
        }
    }
}
