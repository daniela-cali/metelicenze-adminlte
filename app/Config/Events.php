<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;
use CodeIgniter\Shield\Entities\User;

helper('html'); // per poter usare esc()
/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn($buffer) => $buffer);
    }
    Events::on('DBQuery', function ($query) {
        // Scrive ogni query nel log
        log_message('debug', 'SQL: ' . $query->getQuery());
    });
    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

/*
 |--------------------------------------------------------------------
 | Evento: Nuova Registrazione Utente
 |--------------------------------------------------------------------
 | Ogni volta che un utente si registra, inviamo una mail
 | all’amministratore con i dettagli principali.
 */
Events::on('register', static function (User $user) {
    $user->addGroup('pending');
    $email = Services::email();

    // Mittente (Impostato in Config/Email.php)

    // Destinatario: admin
    $admin = config('SiteConfig')->adminEmail;
    $email->setTo($admin);
    $email->setSubject('Nuova registrazione utente su MeTe Licenze');

    // Corpo HTML dell’email
    $content = "
        <br>Nuovo utente registrato su MeTe Licenze</strong><br>
        <p>Un nuovo utente si è appena registrato su <strong>MeTe Licenze</strong>. Ecco i dettagli:</p>
        <p>Proviene da IP: " . esc($_SERVER['REMOTE_ADDR'] ?? 'N/A') . "</p>
        <p>Autorizzare utente se necessario.</p>
        <div class=\"details\">
            <p><strong>Username:</strong> " . $user->username . "</p>
            <p><strong>Email:</strong> " . $user->email . "</p>
            <p><strong>Registrato il:</strong> " . date('d/m/Y H:i') . "</p>
        </div>";
    $message = view('emails/layout', [
        'title'   => 'Nuova registrazione utente su MeTe Licenze',
        'content' => $content
    ]);
    $email->setMessage($message);

    $email->setMailType('html'); // fondamentale per HTML

    if (! $email->send()) {
        // log in caso di errore
        log_message('error', 'Errore invio mail admin nuova registrazione: ' . $email->printDebugger(['headers']));
    } else {
        log_message('info', 'Notifica inviata a admin per nuova registrazione utente: ' . $user->email);
    }
});
