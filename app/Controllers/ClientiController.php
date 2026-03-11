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
    protected $backTo;
    public function __construct()
    {
        $this->ClientiModel = new ClientiModel();
        $this->LicenzeModel = new LicenzeModel();
        $this->backTo = base_url('/clienti');
    }


    public function index(): string
    {


        $data['clienti'] = $this->ClientiModel->getClienti();
        $licenzeCount = $this->LicenzeModel->countLicenzeByCliente();
        $licenzeTipo = $this->LicenzeModel->getTipoLicenzeByCliente();

        foreach ($data['clienti'] as &$cliente) {
            $id = $cliente["id"];
            log_message('info', '============== Elaboro il cliente con ID: ' . $id . ' - Nome: ' . $cliente["nome"]);
            //log_message('info', 'Numero di licenze trovate: ' . ($licenzeCount[$id] ?? 0));    
            $cliente["numLicenze"] = $licenzeCount[$id] ?? 0;
            $cliente["tipiLicenze"] = isset($licenzeTipo[$id]) ? $licenzeTipo[$id] : [];
            //log_message('info', '==============Cliente ID elaboranto con successo: ' . $cliente["id"] . ' - Nome: ' . $cliente["nome"] . ' - NumLicenze: ' . $cliente["numLicenze"] . ' - TipiLicenze: ' . print_r($cliente["tipiLicenze"], true));
        }
        unset($cliente); // Termina la referenza
        $data['title'] = 'Elenco Clienti';
        //session()->set(['route'=>'clienti']);

        return view('clienti/index', $data);
    }

        public function show($id)
    {
        $this->backTo = base_url('/clienti/' . $id);

        $session = session();

        $data['cliente'] = $this->ClientiModel->getClientiById($id);

        $session->set('current_cliente_id', $data['cliente']["id"]);
        $session->set('current_padre_id', $data['cliente']["padre_id"]);

        if ($data['cliente']["padre_id"]) {
            $padre = $this->ClientiModel->getClientiById($data['cliente']['padre_id']);
            $data['cliente']['padre_nome'] = $padre['nome'] ?? null;
        }

        $data['licenze'] = $this->LicenzeModel->getLicenzeByCliente($id);

        $data['title'] = 'Scheda Cliente';

        return view('clienti/show', $data);
    }

    public function create()
    {
        $backTo = $this->resolveBackTo(base_url('/clienti'));
        return view('clienti/form', [
            'title' => 'Crea Nuovo Cliente',
            'mode' => 'create',
            'fornitore' => null,
            'backTo' => $backTo,
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
                $this->resolveBackTo(base_url('/clienti/' . $clienteID))
            )->with('success', 'Cliente creato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione del cliente.')->withInput();
        }
    }



    public function edit($id)
    {
        $backTo = $this->resolveBackTo(base_url('/clienti'));
        $cliente = $this->ClientiModel->getClientiById($id);
        $selectValues = $this->ClientiModel->getClientiPadre();
        return view('clienti/form', [
            'mode' => 'edit',
            'cliente' => $cliente,
            'action' => '/clienti/salva/' . $id,
            'title' => 'Modifica Cliente ' . $cliente["nome"],
            'selectValues' => $selectValues,
            'backTo' => $backTo,
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id; // Aggiungo l'ID per la modifica
        if ($this->ClientiModel->save($data)) {
            return redirect()->to(
                $this->resolveBackTo(base_url('/clienti/' . $id))
            )->with('success', 'Cliente aggiornato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento del cliente.')->withInput();
        }
    }

        public function delete($id)
    {
        if ($this->ClientiModel->delete($id)) {
            return redirect()->to($this->resolveBackTo(base_url('/clienti')))
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
