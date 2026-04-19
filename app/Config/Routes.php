<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');

service('auth')->routes($routes);

$routes->group('filters', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'FiltersController::index');
    $routes->post('apply', 'FiltersController::applyFilters');
    $routes->get('clear', 'FiltersController::clearFilters');
});

$routes->group('clienti', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'ClientiController::index', ['as' => 'clienti_index']);
    $routes->get('(:num)', 'ClientiController::show/$1', ['as' => 'clienti_scheda']);
    $routes->get('crea', 'ClientiController::create', ['as' => 'clienti_crea']);
    $routes->post('/', 'ClientiController::store', ['as' => 'clienti_salva']);
    $routes->get('modifica/(:num)', 'ClientiController::edit/$1', ['as' => 'clienti_modifica']);
    $routes->put('(:num)', 'ClientiController::update/$1', ['as' => 'clienti_aggiorna']);
    $routes->get('elimina/(:num)', 'ClientiController::delete/$1', ['as' => 'clienti_elimina']);
});

$routes->group('licenze', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'LicenzeController::index', ['as' => 'licenze_index']);
    $routes->get('(:num)', 'LicenzeController::show/$1', ['as' => 'licenze_scheda']);
    $routes->get('crea/(:num)', 'LicenzeController::create/$1', ['as' => 'licenze_crea']); // Passo l'ID del cliente alla funzione create
    $routes->post('/', 'LicenzeController::store', ['as' => 'licenze_salva']);
    $routes->get('modifica/(:num)', 'LicenzeController::edit/$1', ['as' => 'licenze_modifica']);
    $routes->put('(:num)', 'LicenzeController::update/$1', ['as' => 'licenze_aggiorna']);
    $routes->get('elimina/(:num)', 'LicenzeController::delete/$1', ['as' => 'licenze_elimina']);
});

$routes->group('aggiornamenti', ['filter' => 'notpending'], function($routes) {
    $routes->get('byLicenza/(:num)', 'AggiornamentiController::getByLicenza/$1', ['as' => 'aggiornamenti_byLicenza']);
    $routes->get('(:num)', 'AggiornamentiController::show/$1', ['as' => 'aggiornamenti_scheda']);
    $routes->get('crea/(:num)/(:segment)', 'AggiornamentiController::create/$1/$2', ['as' => 'aggiornamenti_crea']); // Creo un nuovo aggiornamento passando l'ID della licenza e il tipo
    $routes->post('salva/(:num)', 'AggiornamentiController::store/$1', ['as' => 'aggiornamenti_salva']);
    $routes->get('modifica/(:num)', 'AggiornamentiController::edit/$1', ['as' => 'aggiornamenti_modifica']);
    $routes->get('elimina/(:num)', 'AggiornamentiController::delete/$1', ['as' => 'aggiornamenti_elimina']);
});

$routes->group('versioni', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'VersioniController::index', ['as' => 'versioni_index']);
    $routes->get('(:num)', 'VersioniController::show/$1', ['as' => 'versioni_scheda']);
    $routes->get('crea/(:num)', 'VersioniController::create/$1', ['as' => 'versioni_crea']);
    $routes->get('modifica/(:num)', 'VersioniController::edit/$1', ['as' => 'versioni_modifica']);
    $routes->post('salva/(:num)', 'VersioniController::store/$1', ['as' => 'versioni_salva']);
    $routes->get('elimina/(:num)', 'VersioniController::delete/$1', ['as' => 'versioni_elimina']);
});

$routes->group('fornitori', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'FornitoriController::index', ['as' => 'fornitori_index']);
    $routes->get('(:num)', 'FornitoriController::show/$1', ['as' => 'fornitori_scheda']);
    $routes->get('crea', 'FornitoriController::create', ['as' => 'fornitori_crea']);
    $routes->post('/', 'FornitoriController::store', ['as' => 'fornitori_salva']);
    $routes->get('modifica/(:num)', 'FornitoriController::edit/$1', ['as' => 'fornitori_modifica']);
    $routes->put('(:num)', 'FornitoriController::update/$1', ['as' => 'fornitori_aggiorna']);
    $routes->get('elimina/(:num)', 'FornitoriController::delete/$1', ['as' => 'fornitori_elimina']);
});

// Rinominato da 'tipi' a 'tipilicenze' per allinearsi al nome del controller TipiLicenzeController
$routes->group('tipilicenze', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'TipiLicenzeController::index', ['as' => 'tipilicenze_index']);
    $routes->get('(:num)', 'TipiLicenzeController::show/$1', ['as' => 'tipilicenze_scheda']);
    $routes->get('new', 'TipiLicenzeController::create', ['as' => 'tipilicenze_crea']);
    $routes->post('/', 'TipiLicenzeController::store', ['as' => 'tipilicenze_salva']);
    $routes->get('edit/(:num)', 'TipiLicenzeController::edit/$1', ['as' => 'tipilicenze_modifica']);
    $routes->put('(:num)', 'TipiLicenzeController::update/$1', ['as' => 'tipilicenze_aggiorna']);
    $routes->get('delete/(:num)', 'TipiLicenzeController::delete/$1', ['as' => 'tipilicenze_elimina']);
    $routes->post('link/(:num)', 'TipiLicenzeController::link/$1', ['as' => 'tipilicenze_link']);
    $routes->get('unlink/(:num)', 'TipiLicenzeController::unlink/$1', ['as' => 'tipilicenze_unlink']);
});

$routes->group('admin', ['filter' => 'group:superadmin,admin'], function($routes) {
    /**
     * Importazione Clienti 
     */
    $routes->get('importClienti', 'Admin\ImportClientiController::index');
    $routes->post('importClienti', 'Admin\ImportClientiController::importClienti');

    /**
     * Impostazioni sito 
     */
    $routes->get('settings', 'Admin\SettingsController::index');
    $routes->post('settings/save', 'Admin\SettingsController::save');

    /**
     * Gestione utenti — rotte per CRUD completo su utenti, con approvazione e eliminazione.
     * Il filter 'group:superadmin,admin' assicura che solo gli amministratori possano accedere a queste rotte.
     */
    $routes->get('users',                'Admin\UsersController::index',       ['as' => 'users_index']);
    $routes->get('users/(:num)',          'Admin\UsersController::show/$1',     ['as' => 'users_scheda']);
    $routes->get('users/crea',           'Admin\UsersController::create',      ['as' => 'users_crea']);
    $routes->post('users',               'Admin\UsersController::store',       ['as' => 'users_salva']);
    $routes->get('users/modifica/(:num)', 'Admin\UsersController::edit/$1',    ['as' => 'users_modifica']);
    $routes->put('users/(:num)',          'Admin\UsersController::update/$1',  ['as' => 'users_aggiorna']);
    $routes->get('users/elimina/(:num)',  'Admin\UsersController::delete/$1',  ['as' => 'users_elimina']);
    $routes->get('users/approva/(:num)', 'Admin\UsersController::approva/$1', ['as' => 'users_approva']);

    /**
     * Database Info — rotte per testare la connessione al database esterno e visualizzare informazioni utili per debug e sviluppo.
     * Queste rotte sono protette dal filter 'group:superadmin,admin' perché espongono dettagli tecnici che non dovrebbero essere accessibili a utenti non amministratori.
     */
    $routes->get('databaseinfo',                               'Admin\DatabaseInfoController::connectionTest', ['as' => 'databaseinfo_connectiontest']);
    $routes->get('databaseinfo/info/(:segment)',               'Admin\DatabaseInfoController::info/$1', ['as' => 'databaseinfo_info']);
    $routes->get('databaseinfo/fields/(:segment)/(:segment)', 'Admin\DatabaseInfoController::getTableFields/$1/$2', ['as' => 'databaseinfo_fields']);
    $routes->get('databaseinfo/showlog',                       'Admin\DatabaseInfoController::showLog', ['as' => 'databaseinfo_showlog']);
});

$routes->group('account', function($routes) {
    $routes->get('pending', 'AccountController::pending');
    $routes->get('nodev', 'AccountController::nodev');
});

$routes->group('test', function($routes) {
    $routes->get('log', 'TestController::logTestMessage');
    $routes->get('db', 'TestController::testDatabaseConnection');
});

// =============================================================================
// SKELETON ROTTE — copiare e adattare per ogni nuovo gruppo CRUD standard.
// Sostituire 'gruppo' con il nome risorsa (es. 'prodotti') e
// 'GruppoController' con il nome del controller corrispondente.
// Il filter 'notpending' richiede che l'utente non sia in stato pending;
// usare 'group:superadmin,admin' per le aree riservate agli amministratori.
// Inoltre, se controller e route hanno lo stesso nome, tablemanager.js aggiunge automaticamente i link alle rotte standard, quindi è importante usare nomi coerenti per controller e rotte (es. 'FornitoriController' e 'fornitori').
// =============================================================================
//
// $routes->group('gruppo', ['filter' => 'notpending'], function($routes) {
//     $routes->get('/',              'GruppoController::index',    ['as' => 'gruppo_index']);     // Elenco di tutti i record
//     $routes->get('(:num)',         'GruppoController::show/$1',  ['as' => 'gruppo_scheda']);    // Dettaglio in sola lettura
//     $routes->get('crea',          'GruppoController::create',   ['as' => 'gruppo_crea']);      // Form di creazione vuoto
//     $routes->post('/',             'GruppoController::store',    ['as' => 'gruppo_salva']);     // Salva il nuovo record (POST dal form crea)
//     $routes->get('modifica/(:num)','GruppoController::edit/$1',  ['as' => 'gruppo_modifica']); // Form di modifica precompilato
//     $routes->put('(:num)',         'GruppoController::update/$1',['as' => 'gruppo_aggiorna']); // Salva le modifiche (PUT dal form modifica)
//     $routes->get('elimina/(:num)', 'GruppoController::delete/$1',['as' => 'gruppo_elimina']); // Elimina il record
// });
