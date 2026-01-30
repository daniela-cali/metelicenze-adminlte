<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    /**
     * The default database connection group.
     */
    public string $defaultGroup = 'default';

    /**
     * CI requires these properties to exist, but we populate them at runtime.
     *
     * @var array<string, mixed>
     */
    public array $default  = [];
    public array $external = [];

    public function __construct()
    {
        parent::__construct();
        //Tutto ciò che usa l'env() va messo nel costruttore perché altrimenti non funziona
        // DEFAULT (MySQL)
        $this->default = [
            'DSN'      => '',
            'hostname' => env('database.default.hostname'),
            'username' => env('database.default.username'),
            'password' => env('database.default.password'),
            'database' => env('database.default.database'),
            'DBDriver' => env('database.default.DBDriver'),
            'DBPrefix' => env('database.default.DBPrefix'),
            'pConnect' => false,
            'charset'  => env('database.default.charset')?? 'utf8mb4',
            'DBDebug'  => ENVIRONMENT !== 'production',
            'port'     => (int) env('database.default.port'),
            'swapPre'  => '',
            'failover' => [],
            'dateFormat' => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
        ];

        // EXTERNAL
        $this->external = [
            'DSN'      => '',
            'hostname' => env('database.external.hostname'),
            'username' => env('database.external.username'),
            'password' => env('database.external.password'),
            'database' => env('database.external.database'),
            'charset'  => env('database.external.charset')?? 'latin1',
            'DBDriver' => env('database.external.DBDriver'),
            'DBPrefix' => env('database.external.DBPrefix'),
            'pConnect' => false,
            'DBDebug'  => ENVIRONMENT !== 'production',
            'port'     => (int) env('database.external.port'),
            'schema'   => env('database.external.schema'),

            // timeout breve quando Postgres non c'è
            // (molto spesso è più compatibile così che dentro 'options')
            'connect_timeout' => 2,

            'swapPre'  => '',
            'failover' => [],
            'dateFormat' => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
        ];

        // Test suite safety
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
