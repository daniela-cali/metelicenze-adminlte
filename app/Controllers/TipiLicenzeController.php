<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TipiLicenzeController extends BaseController
{
    protected $TipiLicenzeModel;
    protected $FornitoriTipilicenzeMapModel;   
    protected $FornitoriModel; 
    protected $backTo;
    public function __construct()
    {
        $this->TipiLicenzeModel = new \App\Models\TipiLicenzeModel();
        $this->FornitoriTipilicenzeMapModel = new \App\Models\FornitoriTipilicenzeMapModel();
        $this->FornitoriModel = new \App\Models\FornitoriModel();
        $this->backTo = session()->get('backTo') ?? base_url('/tipi');
    }
    public function index()
    {
        $data['tipiLicenze'] = $this->TipiLicenzeModel->getTipiLicenza();
        $data['title'] = 'Elenco Tipi Licenze';
        return view('licenze/tipi/index', $data);
    }
    public function show($id)
    {
        $data['tipo'] = $this->TipiLicenzeModel->find($id);
        if (!$data['tipo']) {
            return redirect()->to('licenze/tipi')->with('error', 'Tipo di licenza non trovato');
        }
        $data['mode'] = 'show';
        $data['title'] = 'Dettagli Tipo Licenza: ' . esc($data['tipo']['nome']);
        $data['form'] = [
            'action' => null,
            'method' => null,
            'spoof' => null,
            'readonly' => true
            ];
        return view('licenze/tipi/show', $data);
    }

    public function create()
    {
        $data = array(
            'mode' => 'create',
            'title' => 'Crea Nuova Tipologia di Licenza',
            'form' => [
                'action' => url_to('tipi_store'),
                'method' => 'POST',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => false,
            ]
        );
        return view('licenze/tipi/form', $data);
    }
    public function store()
    {
        $data = $this->request->getPost();
        if (!$data) {
            return redirect()->back()->with('error', 'Dati mancanti per creare il tipo di licenza.');
        }
        if ($this->TipiLicenzeModel->insert($data)) {
            return redirect()->to('tipi')->with('success', 'Tipo di licenza creato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione del tipo di licenza.')->withInput();
        }
    }
    public function edit($id)
    {
        $tipoLicenza = $this->TipiLicenzeModel->find($id);
        $data = array(
            'mode' => 'edit',
            'tipoLicenza' => $tipoLicenza,
            'title' => 'Modifica Tipo Licenza: ' . $tipoLicenza["nome"],
            'form' => [
                'action' => url_to('tipi_update', $id),
                'method' => 'POST',
                'spoof' => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly' => false,
            ]
        );
        return view('licenze/tipi/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id; // Aggiungo l'ID per la modifica
        if ($this->TipiLicenzeModel->save($data)) {
            return redirect()->to('tipi/' . $id)->with('success', 'Tipo di licenza aggiornato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento del tipo di licenza.')->withInput();
        }
    }

    public function delete($id)
    {
        if($this->TipiLicenzeModel->delete($id)) {
            return redirect()->back()->with('success', 'Tipo di licenza eliminato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del tipo di licenza.');
        }
    }
    
    public function link($idFornitore)
    {  
        $data = $this->request->getPost();
        //dd($data);
        $idLicenza = $data['id_licenza'] ?? null;

        if (!$idLicenza) {
            return redirect()->back()->with('error', 'ID licenza mancante per l\'associazione.');
        }
        $result = $this->FornitoriTipilicenzeMapModel->linkFornitoreToTipoLicenze($idFornitore, $idLicenza);

        if ($result !== false) {
            return redirect()->back()->with('success', 'Tipo di licenza associato al fornitore con successo.');
        }

        return redirect()->back()->with('error', 'Errore durante l\'associazione del tipo di licenza al fornitore.');

    }


    


}
