<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FornitoriModel;

use CodeIgniter\HTTP\ResponseInterface;

class FornitoriController extends BaseController
{

    protected $FornitoriModel;
    protected $tipiLicenzeModel;
    protected $fornitoriTipi_map;

    public function __construct()
    {
        $this->FornitoriModel = new FornitoriModel();
        $this->tipiLicenzeModel = new \App\Models\TipiLicenzeModel();
        $this->fornitoriTipi_map = new \App\Models\FornitoriTipilicenzeMapModel();
    }


    public function index(): string
    {
        $data = [
            'fornitori' => $this->FornitoriModel->getFornitori(),
            'title' => 'Elenco Fornitori'
        ];

        foreach ($data['fornitori'] as &$fornitore) {
            $id = $fornitore["id"];
            log_message('info', '==============Elaboro il fornitore con ID: ' . $id . ' - Nome: ' . $fornitore["nome"]);
            $fornitore["tipiLicenze"] = $this->tipiLicenzeModel->getTipiLicenzeByFornitore($id);
            //log_message('info', '==============Fornitore ID elaborato con successo: ' . $fornitore["id"] . ' - Nome: ' . $fornitore["nome"] . ' - NumLicenze: ' . $fornitore["numLicenze"] . ' - TipiLicenze: ' . print_r($fornitore["tipiLicenze"], true));
        }
        unset($fornitore); // Termina la referenza*/
        $data['title'] = 'Elenco Fornitori';
        return view('fornitori/index', $data);
    }

    public function show($id)
    {
        $fornitore = $this->FornitoriModel->getFornitoriById($id);
        $data = [
            'mode' => 'view',
            'fornitori' => $fornitore,
            'title' => 'Scheda Fornitore' . $fornitore["nome"],
            'selectData' => $this->tipiLicenzeModel->getTipiLicenzaForSelect(),
            'licenzeFornite' => $this->tipiLicenzeModel->getTipiLicenzeByFornitore($id),
            'form' => [
                'action' => '', // Nessuna azione in visualizzazione
                'method' => 'get',
                'spoof' => null,
                'submitText' => '',
                'readonly' => true,
            ]
        ];

        return view('fornitori/show', $data);
    }

    public function create()
    {
        $backTo = $this->getBackTo(url_to('fornitori_index'));
        $data = [
            'title' => 'Crea Nuovo Fornitore',
            'mode' => 'create',
            'fornitore' => null,
            'backTo' => $backTo,
            'form' => [
                'action' => site_url('fornitori'),
                'method' => 'post',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => false,

            ]
        ];
        return view('fornitori/form', $data );
    }

    public function store()
    {
        $data = $this->request->getPost();
        if (!$data) {
            return redirect()->back()->with('error', 'Dati mancanti per creare il fornitore.');
        }
        if ($this->FornitoriModel->save($data)) {
            $fornitoreID = $this->FornitoriModel->getInsertID();
            return redirect()->to(
                $this->getBackTo(base_url('/fornitori/' . $fornitoreID))
            )->with('success', 'Fornitore creato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione del fornitore.')->withInput();
        }
    }

    public function edit($id)
    {
        $fornitore = $this->FornitoriModel->getFornitoriById($id);
        $backTo = $this->getBackTo(base_url('/fornitori'));
        $data = [
            'mode' => 'edit',
            'fornitore' => $fornitore,

            'title' => 'Modifica Fornitore ' . $fornitore["nome"],
            'backTo' => $backTo,
            'form' => [
                'action' => url_to('fornitori_salva', $id),
                'method' => 'POST',
                'spoof' => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly' => false,
            ],
        ];
        return view('fornitori/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id; // Aggiungo l'ID per la modifica
        if ($this->FornitoriModel->save($data)) {
            return redirect()->to(
                $this->getBackTo(base_url('/fornitori/' . $id))
            )->with('success', 'Fornitore aggiornato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento del fornitore.')->withInput();
        }
    }

    public function delete($id)
    {
        if ($this->FornitoriModel->delete($id)) {
            return redirect()->to($this->getBackTo(base_url('/fornitori')))
                ->with('success', 'Fornitore eliminato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del fornitore.');
        }
    }
}
