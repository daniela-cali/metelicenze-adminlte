<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FornitoriModel;
use App\Models\TipiLicenzeModel;
use App\Controllers\TipiLicenzeController;

use CodeIgniter\HTTP\ResponseInterface;

class FornitoriController extends BaseController
{

    protected FornitoriModel $FornitoriModel;
    protected TipiLicenzeModel $tipiLicenzeModel;


    public function __construct()
    {
        $this->FornitoriModel = new FornitoriModel();
        $this->tipiLicenzeModel = new \App\Models\TipiLicenzeModel();
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
        return $this->view('fornitori/index', $data);
    }

    public function show(int $id)
    {
        $tipiLicenzeController = (new TipiLicenzeController);
        $fornitore = $this->FornitoriModel->getFornitoriById($id);
        $data = [
            'mode'          => 'view',
            'fornitore'     => $fornitore,
            'title'         => 'Scheda Fornitore: ' . $fornitore["nome"],
            'backTo'        => $this->getBackTo(url_to('fornitori_index')),
            'selectData'    => $tipiLicenzeController->getTipiLicenzaForSelect(),
            'licenzeFornite' => $this->tipiLicenzeModel->getTipiLicenzeByFornitore($id),
            'form' => [
                'action'     => '',
                'method'     => 'get',
                'spoof'      => null,
                'submitText' => '',
                'readonly'   => true,
            ]
        ];
        return $this->view('fornitori/show', $data);
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
        return $this->view('fornitori/form', $data );
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

    public function edit(int $id)
    {
        $fornitore = $this->FornitoriModel->getFornitoriById($id);
        $data = [
            'mode' => 'edit',
            'fornitore' => $fornitore,
            'title' => 'Modifica Fornitore ' . $fornitore["nome"],
            'backTo' => $this->getBackTo(base_url('/fornitori')),
            'form' => [
                'action' => url_to('fornitori_store', $id),
                'method' => 'POST',
                'spoof' => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly' => false,
            ],
        ];
        return $this->view('fornitori/form', $data);
    }

    public function update(int $id)
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

    public function delete(int $id)
    {
        if ($this->FornitoriModel->delete($id)) {
            return redirect()->to($this->getBackTo(base_url('/fornitori')))
                ->with('success', 'Fornitore eliminato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del fornitore.');
        }
    }
}
