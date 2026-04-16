<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Scheletro di controller CRUD standard.
 * Rinominare la classe, il file e i model di conseguenza.
 * I nomi dei metodi corrispondono alle rotte definite in Config/Routes.php
 * per i gruppi che usano il pattern REST standard del progetto:
 *
 *   GET    /gruppo/              → index()
 *   GET    /gruppo/(:num)        → show($id)
 *   GET    /gruppo/crea          → create()
 *   POST   /gruppo/              → store()
 *   GET    /gruppo/modifica/(:num) → edit($id)
 *   PUT    /gruppo/(:num)        → update($id)
 *   GET    /gruppo/elimina/(:num) → delete($id)
 */
class SkeletonController extends BaseController
{
    // -------------------------------------------------------------------------
    // Model
    // -------------------------------------------------------------------------

    /** @var \App\Models\TODO_MODEL */
    protected $Model;

    public function __construct()
    {
        // Sostituire con il model corretto
        // $this->Model = new \App\Models\TODO_MODEL();
    }

    // -------------------------------------------------------------------------
    // Metodi CRUD
    // -------------------------------------------------------------------------

    /**
     * GET /gruppo/
     * Mostra l'elenco di tutti i record.
     * Corrisponde alla rotta: $routes->get('/', 'XxxController::index', ['as' => 'xxx_index']);
     */
    public function index()
    {
        // $records = $this->Model->findAll();

        $data = [
            'title'   => 'Elenco TODO',
            // 'records' => $records,
        ];

        return $this->view('TODO/index', $data);
    }

    /**
     * GET /gruppo/(:num)
     * Mostra il dettaglio di un singolo record in sola lettura.
     * Corrisponde alla rotta: $routes->get('(:num)', 'XxxController::show/$1', ['as' => 'xxx_scheda']);
     *
     * @param int $id ID del record da visualizzare
     */
    public function show(int $id)
    {
        // $record = $this->Model->find($id);

        // if (!$record) {
        //     return redirect()->to('TODO')->with('error', 'Record non trovato.');
        // }

        $data = [
            'title'  => 'Scheda TODO',
            'mode'   => 'view',
            // 'record' => $record,
            'backTo' => $this->resolveBackTo(base_url('TODO')),
            'form'   => [
                'action'   => site_url('TODO'),
                'method'   => 'post',
                'spoof'    => null,
                'readonly' => true,
            ]
        ];

        return $this->view('TODO/form', $data);
    }

    /**
     * GET /gruppo/crea
     * Mostra il form vuoto per la creazione di un nuovo record.
     * Corrisponde alla rotta: $routes->get('crea', 'XxxController::create', ['as' => 'xxx_crea']);
     */
    public function create()
    {
        $data = [
            'title'   => 'Crea TODO',
            'mode'    => 'create',
            'record'  => null,
            'backTo'  => $this->resolveBackTo(base_url('TODO')),
            'form'    => [
                'action'     => site_url('TODO'),
                'method'     => 'post',
                'spoof'      => null,
                'submitText' => 'Salva',
                'readonly'   => false,
            ]
        ];

        return $this->view('TODO/form', $data);
    }

    /**
     * POST /gruppo/
     * Riceve i dati del form di creazione, li valida e li salva.
     * Corrisponde alla rotta: $routes->post('/', 'XxxController::store', ['as' => 'xxx_salva']);
     */
    public function store()
    {
        // Regole di validazione
        $rules = [
            // 'campo' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            // Ritorno al form con gli errori e i dati inseriti
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            // 'campo' => $this->request->getPost('campo'),
        ];

        // $this->Model->insert($data);

        return redirect()->to(base_url('TODO'))->with('success', 'Record creato con successo.');
    }

    /**
     * GET /gruppo/modifica/(:num)
     * Mostra il form precompilato con i dati del record da modificare.
     * Corrisponde alla rotta: $routes->get('modifica/(:num)', 'XxxController::edit/$1', ['as' => 'xxx_modifica']);
     *
     * @param int $id ID del record da modificare
     */
    public function edit(int $id)
    {
        // $record = $this->Model->find($id);

        // if (!$record) {
        //     return redirect()->to('TODO')->with('error', 'Record non trovato.');
        // }

        $data = [
            'title'  => 'Modifica TODO (ID: ' . esc($id) . ')',
            'mode'   => 'edit',
            // 'record' => $record,
            'backTo' => $this->resolveBackTo(base_url('TODO')),
            'form'   => [
                'action'     => site_url('TODO/' . $id),
                'method'     => 'post',
                'spoof'      => null,
                'submitText' => 'Salva',
                'readonly'   => false,
            ]
        ];

        return $this->view('TODO/form', $data);
    }

    /**
     * PUT /gruppo/(:num)
     * Riceve i dati del form di modifica, li valida e aggiorna il record.
     * Corrisponde alla rotta: $routes->put('(:num)', 'XxxController::update/$1', ['as' => 'xxx_aggiorna']);
     *
     * @param int $id ID del record da aggiornare
     */
    public function update(int $id)
    {
        // Regole di validazione (stesse dello store, o diverse se serve)
        $rules = [
            // 'campo' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            // 'campo' => $this->request->getPost('campo'),
        ];

        // $this->Model->update($id, $data);

        return redirect()->to(base_url('TODO'))->with('success', 'Record aggiornato con successo.');
    }

    /**
     * GET /gruppo/elimina/(:num)
     * Elimina il record specificato.
     * Corrisponde alla rotta: $routes->get('elimina/(:num)', 'XxxController::delete/$1', ['as' => 'xxx_elimina']);
     *
     * @param int $id ID del record da eliminare
     */
    public function delete(int $id)
    {
        // $this->Model->delete($id);

        return redirect()->to($this->resolveBackTo(base_url('TODO')))
            ->with('success', 'Record eliminato con successo.');
    }
}
