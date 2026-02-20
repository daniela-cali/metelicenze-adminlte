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
    protected $backTo;
    public function __construct()
    {
        $this->FornitoriModel = new FornitoriModel();
        $this->tipiLicenzeModel = new \App\Models\TipiLicenzeModel();
        $this->fornitoriTipi_map = new \App\Models\FornitoriTipilicenzeMapModel();
        $this->backTo = session()->get('backTo') ?? base_url('/fornitori');
    }


    public function index(): string
    {
        $data['fornitori'] = $this->FornitoriModel->getFornitori();
        $tipiLicenzeModel = new \App\Models\FornitoriTipilicenzeMapModel();
        /*foreach ($data['fornitori'] as &$fornitore) {
            $idFornitore = $fornitore['id'];
            $fornitore['tipiLicenze'] = $tipiLicenzeModel->getTipiLicenzaByFornitore($idFornitore);
        }*/


        foreach ($data['fornitori'] as &$fornitore) {
            $id = $fornitore["id"];
            log_message('info', '==============Elaboro il fornitore con ID: ' . $id . ' - Nome: ' . $fornitore["nome"]);
            //log_message('info', 'Numero di licenze trovate: ' . ($licenzeCount[$id] ?? 0));    
            //$fornitore["numLicenze"] = $licenzeCount[$id] ?? 0;    
            $fornitore["tipiLicenze"] = $this->fornitoriTipi_map->getTipiLicenzeByFornitore($id);
            //log_message('info', '==============Fornitore ID elaborato con successo: ' . $fornitore["id"] . ' - Nome: ' . $fornitore["nome"] . ' - NumLicenze: ' . $fornitore["numLicenze"] . ' - TipiLicenze: ' . print_r($fornitore["tipiLicenze"], true));
        }
        unset($fornitore); // Termina la referenza*/
        $data['title'] = 'Elenco Fornitori';
        //session()->set(['route'=>'fornitori']);

        return view('fornitori/index', $data);
    }

    public function create()
    {
        return view('fornitori/form', [
            'title' => 'Crea Nuovo Fornitore',
            'mode' => 'create',
            'fornitore' => null,
            'form' => [
                'action' => site_url('fornitori'),
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
            return redirect()->back()->with('error', 'Dati mancanti per creare il fornitore.');
        }
        if ($this->FornitoriModel->save($data)) {
            $fornitoreID = $this->FornitoriModel->getInsertID();
            return redirect()->to('fornitori/' . $fornitoreID)->with('success', 'Fornitore creato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione del fornitore.')->withInput();
        }
    }


    public function show($id)
    {
        $this->backTo = base_url('fornitori'); // Imposto il path di ritorno alla lista dei fornitori

        //log_message('info', 'FornitoriController::schedaFornitore - Path di provenienza: ' . previous_url());
        //log_message('info', 'FornitoriController::schedaFornitore - Path attuale: ' . current_url());
        $session = session();
        $session->set('backTo', $this->backTo);
        $data['fornitore'] = $this->FornitoriModel->getFornitoriById($id);
        $selectData = $this->tipiLicenzeModel->getFornitoriForSelect();

        $data['title'] = 'Scheda Fornitore';

        return view('fornitori/show', $data);
    }

    public function edit($id)
    {
        $fornitore = $this->FornitoriModel->getFornitoriById($id);
        return view('fornitori/form', [
            'mode' => 'edit',
            'fornitore' => $fornitore,
            'action' => 'fornitori/' . $id . '/edit',
            'title' => 'Modifica Fornitore ' . $fornitore["nome"],
            'form' => [
                'action' => site_url('fornitori/' . $id),
                'method' => 'POST',
                'spoof' => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly' => false,
            ],
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id; // Aggiungo l'ID per la modifica
        if ($this->FornitoriModel->save($data)) {
            return redirect()->to('fornitori/' . $id)->with('success', 'Fornitore aggiornato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento del fornitore.')->withInput();
        }

    }

    public function delete($id)
    {
        if($this->FornitoriModel->delete($id)) {
            return redirect()->back()->with('success', 'Fornitore eliminato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del fornitore.');
        }
    }
    
}
