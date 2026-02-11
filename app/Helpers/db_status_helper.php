<?php

use Config\Database;


if (!function_exists('tcp_port_open')) {
    function tcp_port_open(string $host, int $port, float $timeoutSeconds = 1.0): bool
    {
        $errno = 0;
        $errstr = '';

        $fp = @stream_socket_client(
            "tcp://{$host}:{$port}",
            $errno,
            $errstr,
            $timeoutSeconds
        );

        if ($fp !== false) {
            fclose($fp);
            return true;
        }

        return false;
    }
}

if (!function_exists('db_is_available')) {
    function db_is_available($connectionGroup): bool
    {
        $host = (string) env('database.external.hostname');
        $port = (int) env('database.external.port');

        // 1) Rete: fallisce in ~1s invece di 20s
        if (! tcp_port_open($host, $port, 1.0)) {
            return false;
        }
        // 2) Connessione DB: fallisce in ~2s (impostato in Database.php)
        try {
            // "postgres" è il nome del group nel tuo Config\Database
            $db = Database::connect($connectionGroup, false);
            // Forza davvero l'apertura (alcuni driver connettono "lazy")
            $db->initialize();

            // Ping "vero": una query banale
            $db->query('SELECT 1');

            return true;
        } catch (\Throwable $e) {
            // qui NON stampi nulla: logghi e basta (facoltativo)
            log_message('warning', 'Database group "' . $connectionGroup . '" non raggiungibile: {msg}', ['msg' => $e->getMessage()]);
            return false;
        }

    }
}
