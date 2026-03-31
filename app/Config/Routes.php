<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');

service('auth')->routes($routes);

$routes->group('database', ['filter' => 'group:superadmin,admin'], function($routes) {
    $routes->get('/', 'DatabaseInfoController::connectionTest');
    $routes->get('info/(:segment)', 'DatabaseInfoController::info/$1');
    $routes->get('fields/(:segment)/(:segment)', 'DatabaseInfoController::getTableFields/$1/$2');
    $routes->get('showlog', 'DatabaseInfoController::showLog');
});

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
    $routes->get('crea/(:num)', 'LicenzeController::crea/$1', ['as' => 'licenze_crea']);
    $routes->get('modifica/(:num)', 'LicenzeController::modifica/$1', ['as' => 'licenze_modifica']);
    $routes->get('elimina/(:num)', 'LicenzeController::elimina/$1', ['as' => 'licenze_elimina']);
    $routes->get('visualizza/(:num)', 'LicenzeController::visualizza/$1', ['as' => 'licenze_visualizza']);
    $routes->post('salva/(:num)/', 'LicenzeController::salva/$1', ['as' => 'licenze_salva_IDCli']);
    $routes->post('salva/(:num)/(:num)/', 'LicenzeController::salva/$1/$2', ['as' => 'licenze_salva_IDCli_IDLic']);
});

$routes->group('aggiornamenti', ['filter' => 'notpending'], function($routes) {
    $routes->get('byLicenza/(:num)', 'AggiornamentiController::getByLicenza/$1', ['as' => 'aggiornamenti_byLicenza']);
    $routes->get('crea/(:num)/(:segment)', 'AggiornamentiController::crea/$1/$2', ['as' => 'aggiornamenti_crea']);
    $routes->get('modifica/(:num)', 'AggiornamentiController::modifica/$1', ['as' => 'aggiornamenti_modifica']);
    $routes->get('elimina/(:num)', 'AggiornamentiController::elimina/$1', ['as' => 'aggiornamenti_elimina']);
    $routes->get('visualizza/(:num)', 'AggiornamentiController::visualizza/$1', ['as' => 'aggiornamenti_visualizza']);
    $routes->post('salva/(:num)', 'AggiornamentiController::salva/$1', ['as' => 'aggiornamenti_salva']);
});

$routes->group('versioni', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'VersioniController::index', ['as' => 'versioni_index']);
    $routes->get('crea', 'VersioniController::crea', ['as' => 'versioni_crea']);
    $routes->get('modifica/(:num)', 'VersioniController::modifica/$1', ['as' => 'versioni_modifica']);
    $routes->get('elimina/(:num)', 'VersioniController::elimina/$1', ['as' => 'versioni_elimina']);
    $routes->get('visualizza/(:num)', 'VersioniController::visualizza/$1', ['as' => 'versioni_visualizza']);
    $routes->post('salva/(:num)', 'VersioniController::salva/$1', ['as' => 'versioni_salva']);
    $routes->post('salva', 'VersioniController::salva', ['as' => 'versioni_salva_nuova']);
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

$routes->group('tipi', ['filter' => 'notpending'], function($routes) {
    $routes->get('/', 'TipiLicenzeController::index', ['as' => 'tipi_index']);
    $routes->get('(:num)', 'TipiLicenzeController::show/$1', ['as' => 'tipi_scheda']);
    $routes->get('new', 'TipiLicenzeController::create', ['as' => 'tipi_crea']);
    $routes->post('/', 'TipiLicenzeController::store', ['as' => 'tipi_salva']);
    $routes->get('edit/(:num)', 'TipiLicenzeController::edit/$1', ['as' => 'tipi_modifica']);
    $routes->put('(:num)', 'TipiLicenzeController::update/$1', ['as' => 'tipi_aggiorna']);
    $routes->get('delete/(:num)', 'TipiLicenzeController::delete/$1', ['as' => 'tipi_elimina']);
    $routes->post('link/(:num)', 'TipiLicenzeController::link/$1', ['as' => 'tipi_link']);
    $routes->get('unlink/(:num)', 'TipiLicenzeController::unlink/$1', ['as' => 'tipi_unlink']);
});

$routes->group('utenti', ['filter' => 'group:superadmin,admin'], function($routes) {
    $routes->get('/', 'UsersController::index', ['as' => 'utenti_index']);
    $routes->get('crea', 'UsersController::crea', ['as' => 'utenti_crea']);
    $routes->get('modifica/(:num)', 'UsersController::modifica/$1', ['as' => 'utenti_modifica']);
    $routes->get('elimina/(:num)', 'UsersController::elimina/$1', ['as' => 'utenti_elimina']);
    $routes->get('visualizza/(:num)', 'UsersController::visualizza/$1', ['as' => 'utenti_visualizza']);
    $routes->post('salva/(:num)', 'UsersController::salva/$1', ['as' => 'utenti_salva']);
    $routes->get('approva/(:num)', 'UsersController::approva/$1', ['as' => 'utenti_approva']);
    $routes->get('changePassword', 'UsersController::changePassword');
    $routes->post('changePassword', 'UsersController::changePassword');
});

$routes->group('admin', ['filter' => 'group:superadmin,admin'], function($routes) {
    $routes->get('import_clienti', 'Admin\ImportClientiController::index');
    $routes->post('import_clienti', 'Admin\ImportClientiController::importClienti');
    $routes->get('settings', 'Admin\SettingsController::index');
    $routes->post('settings/save', 'Admin\SettingsController::save');
    $routes->get('test-settings', 'Admin\TestSettings::index');
});

$routes->group('account', function($routes) {
    $routes->get('pending', 'AccountController::pending');
    $routes->get('nodev', 'AccountController::nodev');
});

$routes->group('test', function($routes) {
    $routes->get('log', 'TestController::logTestMessage');
    $routes->get('db', 'TestController::testDatabaseConnection');
});
