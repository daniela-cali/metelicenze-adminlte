<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientiModel;
use App\Models\LicenzeModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClientiController extends BaseController
{

    protected $ClientiModel;
    protected $LicenzeModel;

    public function __construct()
    {
        $this->ClientiModel = new ClientiModel();
        $this->LicenzeModel = new LicenzeModel();
    }


    public function index(): string
    {

        $data['clienti'] = $this->ClientiModel->getClienti();
        $licenzeCount = $this->LicenzeModel->countLicenzeByCliente();
        $licenzeTipo = $this->LicenzeModel->getTipoLicenzeByCliente();

        // Mappa id → nome per risolvere il nome del padre
        $clientiMap = array_column($data['clienti'], 'nome', 'id');

        foreach ($data['clienti'] as &$cliente) {
            $id = $cliente["id"];
            log_message('info', '============== Elaboro il cliente con ID: ' . $id . ' - Nome: ' . $cliente["nome"]);
            $cliente["numLicenze"] = $licenzeCount[$id] ?? 0;
            $cliente["tipiLicenze"] = isset($licenzeTipo[$id]) ? $licenzeTipo[$id] : [];
            // Gruppo: se figlio_sn=1 usa il nome del padre, altrimenti il proprio nome
            $cliente["gruppo"] = ($cliente["figlio_sn"] == 1)
                ? ($clientiMap[$cliente["padre_id"]] ?? $cliente["nome"])
                : $cliente["nome"];
        }
        unset($cliente); // Termina la referenza
        $data['title'] = 'Elenco Clienti';
        //session()->set(['route'=>'clienti']);

        return $this->view('clienti/index', $data);
    }

    public function show($id)
    {
        $cliente = $this->ClientiModel->getClientiById($id);
        $session = service('session');
        $session->set('current_cliente_id', $cliente["id"]);
        $session->set('current_padre_id', $cliente["padre_id"]);

        $licenze = $this->LicenzeModel->getLicenzeByCliente($id);

        if ($cliente["padre_id"]) {
            $padre = $this->ClientiModel->getClientiById($cliente['padre_id']);
            $cliente['padre_nome'] = $padre['nome'] ?? null;
        }

        $data = [
            'title' => 'Scheda Cliente ',
            'mode' => 'view',
            'cliente' => $cliente,
            'licenze' => $licenze,
            'backTo' => $this->getBackTo(url_to('clienti_index')),
            'form' => [
                'action' => site_url('clienti'),
                'method' => 'post',
                'spoof' => null,
                'readonly' => true,
            ]
        ];
        return $this->view('clienti/show', $data);
    }

    public function create()
    {
        $backTo = $this->getBackTo(url_to('clienti_index'));
        $internal_code = $this->ClientiModel->generateInternalCode();
        $selectValues = $this->ClientiModel->getClientiPadre();
        return $this->view('clienti/form', [
            'title' => 'Crea Nuovo Cliente',
            'mode' => 'create',
            'cliente' => null,
            'backTo' => $backTo,
            'internal_code' => $internal_code,
            'selectValues' => $selectValues,
            'form' => [
                'action' => site_url('clienti'),
                'method' => 'post',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => false,
            ],
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        if (!$data) {
            return redirect()->back()->with('error', 'Dati mancanti per creare il cliente.');
        }
        if ($this->ClientiModel->save($data)) {
            $clienteID = $this->ClientiModel->getInsertID();
            return redirect()->to(
                $this->getBackTo(url_to('clienti_show', $clienteID))
            )->with('success', 'Cliente salvato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione del cliente.')->withInput();
        }
    }

    public function edit($id)
    {
        log_message('debug', 'ClientiController::edit id: '.$id);
        $backTo = $this->getBackTo(url_to('clienti_index'));
        $cliente = $this->ClientiModel->getClientiById($id);
        $selectValues = $this->ClientiModel->getClientiPadre();
        $data = [
            'title' => 'Modifica Cliente ' . $cliente["nome"],
            'mode' => 'edit',
            'cliente' => $cliente,
            'form' => [
                'action' => url_to('clienti_update', $id),
                'method' => 'post',
                'spoof' => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly' => false,

            ],
            'selectValues' => $selectValues,
            'backTo' => $backTo,
        ];
        return $this->view('clienti/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id; // Aggiungo l'ID per la modifica
        log_message('debug', 'ClientiController::update: '. print_r($data));
        if ($this->ClientiModel->save($data)) {
            return redirect()->to(
                $this->getBackTo(url_to('clienti_show', $id))
            )->with('success', 'Cliente aggiornato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento del cliente.')->withInput();
        }
    }

    public function delete($id)
    {
        if ($this->ClientiModel->delete($id)) {
            return redirect()->to($this->getBackTo(url_to('clienti_index')))
                ->with('success', 'Cliente eliminato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del cliente.');
        }
    }
}

/*
tbana_ragsoc1
tbana_indirizzo1
tbana_citta
tbana_cap
tbana_provincia
tbana_telefono1
*/
