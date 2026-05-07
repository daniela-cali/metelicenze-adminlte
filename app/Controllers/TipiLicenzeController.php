<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TipiLicenzeModel;
use App\Models\FornitoriModel;

class TipiLicenzeController extends BaseController
{
    protected TipiLicenzeModel $TipiLicenzeModel;
    protected FornitoriModel $FornitoriModel;

    public function __construct()
    {
        $this->TipiLicenzeModel = new \App\Models\TipiLicenzeModel();
        $this->FornitoriModel = new \App\Models\FornitoriModel();
    }
    public function index()
    {
        $data['tipiLicenze'] = $this->TipiLicenzeModel->getTipiLicenza();
        $data['title'] = 'Elenco Tipi Licenze';
        return $this->view('licenze/tipi/index', $data);
    }
    public function show(int $id)
    {
        $tipoLicenza = $this->TipiLicenzeModel->find($id);
        if (!$tipoLicenza) {
            return redirect()->to(url_to('tipilicenze_index'))->with('error', 'Tipo di licenza non trovato');
        }
        $data = [
            'mode'        => 'view',
            'title'       => 'Dettagli Tipo Licenza: ' . esc($tipoLicenza['tipo']),
            'backTo'      => $this->getBackTo(url_to('tipilicenze_index')),
            'tipoLicenza' => $tipoLicenza,
            'form' => [
                'action'     => '',
                'method'     => 'get',
                'spoof'      => null,
                'submitText' => '',
                'readonly'   => true,
            ],
        ];
        return $this->view('licenze/tipi/form', $data);
    }

    public function create(): string
    {
        $data = array(
            'mode' => 'create',
            'title' => 'Crea Nuova Tipologia di Licenza',
            'backTo' => $this->getBackTo(base_url('/tipi')),
            'form' => [
                'action' => url_to('tipilicenze_store'),
                'method' => 'POST',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => false,
            ]
        );
        return $this->view('licenze/tipi/form', $data);
    }
    public function store()
    {
        $data = $this->request->getPost();
        if (!$data) {
            return redirect()->back()->with('error', 'Dati mancanti per creare il tipo di licenza.');
        }
        if ($this->TipiLicenzeModel->insert($data)) {
            return redirect()->to($this->getBackTo(base_url('/tipi')))
                ->with('success', 'Tipo di licenza creato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione del tipo di licenza.')->withInput();
        }
    }
    public function edit(int $id): string
    {
        $tipoLicenza = $this->TipiLicenzeModel->find($id);
        $data = array(
            'mode' => 'edit',
            'tipoLicenza' => $tipoLicenza,
            'title' => 'Modifica Tipo Licenza: ' . $tipoLicenza["tipo"],
            'backTo' => $this->getBackTo(base_url('/tipi')),
            'form' => [
                'action' => url_to('tipilicenze_update', $id),
                'method' => 'POST',
                'spoof' => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly' => false,
            ]
        );
        return $this->view('licenze/tipi/form', $data);
    }

    public function update(int $id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id; // Aggiungo l'ID per la modifica
        if ($this->TipiLicenzeModel->save($data)) {
            return redirect()->to($this->getBackTo(base_url('/tipi/' . $id)))
                ->with('success', 'Tipo di licenza aggiornato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento del tipo di licenza.')->withInput();
        }
    }

    public function delete(int $id)
    {
        if ($this->TipiLicenzeModel->delete($id)) {
            return redirect()->to($this->getBackTo(base_url('/tipi')))
                ->with('success', 'Tipo di licenza eliminato con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del tipo di licenza.');
        }
    }

    public function link(int $idFornitore)
    {
        $data = $this->request->getPost();
        //dd($data);
        $idLicenza = $data['id_licenza'] ?? null;

        if (!$idLicenza) {
            return redirect()->back()->with('error', 'ID licenza mancante per l\'associazione.');
        }
        $result = $this->TipiLicenzeModel->linkFornitore($idFornitore, $idLicenza);

        if ($result !== false) {
            return redirect()->back()->with('success', 'Tipo di licenza associato al fornitore con successo.');
        }

        return redirect()->back()->with('error', 'Errore durante l\'associazione del tipo di licenza al fornitore.');
    }
    public function unlink(int $idTipoLicenze)
    {
        if (!$idTipoLicenze) {
            return redirect()->back()->with('error', 'ID licenza mancante ');
        }
        $result = $this->TipiLicenzeModel->unlinkFornitore($idTipoLicenze);

        if ($result !== false) {
            return redirect()->back()->with('success', 'Tipo di licenza disassociato al fornitore con successo.');
        }
        return redirect()->back()->with('error', 'Errore durante la disassociazione del tipo di licenza al fornitore.');
    }

    public function getTipiLicenzaForSelect()
    {
        $data = $this->TipiLicenzeModel->getTipiLicenza();
        $selectData = [];
        foreach ($data as $item) {
            $selectData[] = [
                'id' => $item['id'],
                'value' => $item['tipo'] . ' - ' . $item['modello'],
            ];
        }
        return $selectData;
    }
}
