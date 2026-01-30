<?php

use Config\Database;

function db_is_available($connectionGroup): bool
{
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
