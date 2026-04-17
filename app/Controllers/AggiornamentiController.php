<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AggiornamentiController extends BaseController
{
    protected $VersioniModel;
    protected $AggiornamentiModel;
    protected $LicenzeModel;

    public function __construct()
    {
        $this->VersioniModel = new \App\Models\VersioniModel();
        $this->AggiornamentiModel = new \App\Models\AggiornamentiModel();
        $this->LicenzeModel = new \App\Models\LicenzeModel();
    }

    public function getByLicenza($idLicenza)
    {
        $rows = $this->AggiornamentiModel->getByLicenza($idLicenza);

        //Formatto le date in d/m/Y
        foreach ($rows as &$row) {
            $date = new \DateTime($row["dt_agg"]);
            $row["dt_agg"] = $date->format('d/m/Y');
        }
        log_message('info', 'AggiornamentiController::getByLicenza - Risultato query: ' . print_r($rows, true));
        $result = $this->response->setJSON([
            'data' => $rows
        ]);
        log_message('info', 'AggiornamentiController::getByLicenza - Risposta JSON: ' . $result->getBody());
        return $result;
    }



    public function show($id)
    {
        $backTo = $this->getBackTo(base_url('/licenze'));
        // Logica per visualizzare i dettagli di una licenza
        $aggiornamento = $this->AggiornamentiModel->getById($id);
        $versioni = $this->VersioniModel->getVersioni();

        $data = [
            'mode' => 'view', // Modalità di visualizzazione
            'form' => [
                'action' => '', // Non c'è azione di salvataggio in visualizzazione
                'method' => 'post',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => true, // Rende i campi del form non modificabili
            ],
            'aggiornamento' => $aggiornamento,
            'versioni' => $versioni,
            'title' => 'Dettagli aggiornamento del ' . date('d/m/Y', strtotime($aggiornamento['dt_agg'])),
            'backTo' => $backTo, // Aggiungo il path di provenienza per il bottone indietro
        ];

        return $this->view('aggiornamenti/form', $data);
    }
    public function create($idLicenza = null, $tipo = null)
    {
  
        $backTo = $this->getBackTo(base_url('/licenze'));
        //log_message('info', 'AggiornamentiController::crea - Tipo: ' . $tipo);
        //log_message('info', 'AggiornamentiController::crea - ID Licenza: ' . $idLicenza);
        // Se non è fornito un ID licenza, non posso creare un aggiornamento
        if ($idLicenza === null) {
            return redirect()->back()->with('error', 'Selezionare una licenza!.');
        } else {
            $info = $this->LicenzeModel->getLicenzeById($idLicenza);
        }
        $tipo = $tipo ?? '';
        //log_message('info', 'AggiornamentiController::crea - Creazione aggiornamento per Licenza ID: ' . $idLicenza);
        $versioni = $this->VersioniModel->getVersioniByTipo($tipo);

        $data = [
            'mode' => 'create', // Modalità di creazione
            'licenza_id' => $idLicenza,
            'title' => 'Crea Aggiornamento per Licenza ' . esc($info['codice']) . ' ID ' . esc($idLicenza),
            'versioni' => $versioni,
            'backTo' => $backTo, // Aggiungo il path di provenienza per il bottone indietro
            'form' => [
                'action' => url_to('aggiornamenti_salva', $idLicenza), // Azione per il salvataggio dell'aggiornamento
                'method' => 'post',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => false, // Rende i campi del form modificabili
            ],
        ];


        return $this->view('aggiornamenti/form', $data);
    }

    public function edit($id)
    {
        $backTo = $this->getBackTo(base_url('/licenze'));

        $aggiornamento = $this->AggiornamentiModel->getById($id);
        $versioni = $this->VersioniModel->getVersioni();

        $data = [
            'mode' => 'edit', // Modalità di modifica
            'form' => [
                'action' => url_to('aggiornamenti_salva', $aggiornamento['licenze_id']), // Azione per il salvataggio dell'aggiornamento
                'method' => 'post',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => false,
            ],
            'aggiornamento' => $aggiornamento,
            'versioni' => $versioni,
            'title' => 'Modifica Aggiornamento del ' . date('d/m/Y', strtotime($aggiornamento['dt_agg'])),
            'backTo' => $backTo,
        ];

        return $this->view('aggiornamenti/form', $data);
    }

    public function store($idLicenza = null)
    {
        log_message('info', 'AggiornamentiController::store - ID Licenza: ' . $idLicenza);
        $data = $this->request->getPost(); // Prende tutti i campi del form
        log_message('info', 'AggiornamentiController::store - Dati ricevuti: ' . print_r($data, true));
        $stato = $this->request->getPost('stato') ? 1 : 0; // Converte lo stato in booleano
        // Se non è fornito un ID licenza, non posso salvare l'aggiornamento
        if ($idLicenza === null) {
            return redirect()->back()->with('error', 'Selezionare una licenza!.');
        }
        $data['licenze_id'] = $idLicenza; // Associa l'aggiornamento alla licenza
        $data['stato'] = $stato; // Aggiungo lo stato 

        if ($this->AggiornamentiModel->save($data)) {
            return redirect()->back()->with('success', 'Aggiornamento salvato con successo!');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione dell\'aggiornamento.')->withInput();
        }

    }

    public function delete($id)
    {
        $backTo = $this->getBackTo(base_url('/licenze'));
        // Logica per eliminare una licenza
        $this->AggiornamentiModel->delete($id);
        // Redirect o mostra un messaggio di successo
        return redirect()->to($backTo)->with('success', 'Aggiornamento eliminato con successo.');
    }
}
