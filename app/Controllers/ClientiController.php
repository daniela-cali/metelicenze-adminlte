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
        $this->backTo = session()->get('backTo') ?? base_url('/clienti');
    }

    /*public function __index()
    {
        $tipoLicenza = $this->request->getGet('tipoLicenza');
        if ($tipoLicenza) {
            $idClientiPerLicenza = $this->LicenzeModel->getLicenzeByTipo($tipoLicenza);
            //log_message('info', 'Clienti con licenza di tipo ' . $tipoLicenza . ': ' . print_r($idClientiPerLicenza, true));
            $ids = array_map(fn($licenza) => $licenza->clienti_id, $idClientiPerLicenza);
            if (count($idClientiPerLicenza) > 0) $data['clienti'] = $this->ClientiModel->getClientiByIds($ids);
            //else $data['clienti'] = [];
            log_message('info', 'Clienti filtrati: ' . print_r($data['clienti'], true));
        } else {
            $data['clienti'] = $this->ClientiModel->getClienti();
        }
        $licenzeCount = $this->countLicenzeByCliente();

        foreach ($data['clienti'] as $cliente) {
            $cliente->numLicenze = $licenzeCount[$cliente->id] ?? 0;
        }
        $data['title'] = 'Elenco Clienti';

        return view('clienti/index', $data);
    }*/
    public function index(): string
    {

        //setHistory(base_url('/clienti'));


        $data['clienti'] = $this->ClientiModel->getClienti();
        $licenzeCount = $this->countLicenzeByCliente();
        $licenzeTipo = $this->getTipoLicenzeByCliente();

        //log_message('info', 'Conteggio licenze per cliente: ' . print_r($licenzeCount, true));
        //dd($licenzeCount);

        //dd($data['clienti']);
        foreach ($data['clienti'] as &$cliente) {    
            $id = $cliente["id"];    log_message('info', '==============Elaboro il cliente con ID: ' . $id . ' - Nome: ' . $cliente["nome"]);    
            //log_message('info', 'Numero di licenze trovate: ' . ($licenzeCount[$id] ?? 0));    
            $cliente["numLicenze"] = $licenzeCount[$id] ?? 0;    
            $cliente["tipiLicenze"] = isset($licenzeTipo[$id]) ? $licenzeTipo[$id] : [];    
            //log_message('info', '==============Cliente ID elaboranto con successo: ' . $cliente["id"] . ' - Nome: ' . $cliente["nome"] . ' - NumLicenze: ' . $cliente["numLicenze"] . ' - TipiLicenze: ' . print_r($cliente["tipiLicenze"], true));
        }
        unset ($cliente); // Termina la referenza
        $data['title'] = 'Elenco Clienti';
        //session()->set(['route'=>'clienti']);

        return view('clienti/index', $data);
    }

    public function schedaCliente($id)
    {
        $this->backTo = base_url('/clienti/schedaCliente/'. $id);
        
        //log_message('info', 'ClientiController::schedaCliente - Path di provenienza: ' . previous_url());
        //log_message('info', 'ClientiController::schedaCliente - Path attuale: ' . current_url());
        $session = session();
        //$session->set('backTo', current_url()); // Salvo il path di provenienza nella sessione
        $data['cliente'] = $this->ClientiModel->getClientiById($id);
        //Salvo i dati del cliente corrente nella sessione per usi futuri (nel form delle licenze ad esempio)
        //dd($data['cliente']);
        //Uso indice 0 perchè getClientiById ritorna un array con un solo elemento
        $session->set('current_cliente_id', $data['cliente']["id"]); 
        $session->set('current_padre_id', $data['cliente']["padre_id"]);

        if ($data['cliente']["padre_id"]) {
            $padre = $this->ClientiModel->getClientiById($data['cliente']['padre_id']);
            $data['cliente']['padre_nome'] = $padre['nome'] ?? null;
        }

        $data['licenze'] = $this->LicenzeModel->getLicenzeByCliente($id);

        $data['title'] = 'Scheda Cliente';

        return view('clienti/schedaCliente', $data);
    }
    public function crea()
    {
        /**
         * Creo un nuovo codice distintivo per il cliente interno
         */
        $prefix = 'IN';
        $internal_code = $prefix . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        // Recupero i clienti padre per la select
        $selectValues = $this->ClientiModel->getClientiPadre();

        return view('clienti/form', [
            'mode' => 'create',
            'action' => '/clienti/salva',
            'title' => 'Crea Nuovo Cliente Interno [' . esc($internal_code) . ']',
            'internal_code' => $internal_code,
            'selectValues' => $selectValues,
        ]);
    }

    public function modifica($id)
    {
        $cliente = $this->ClientiModel->getClientiById($id);
        $selectValues = $this->ClientiModel->getClientiPadre();
        return view('clienti/form', [
            'mode' => 'edit',
            'cliente' => $cliente,
            'action' => '/clienti/salva/' . $id,
            'title' => 'Modifica Cliente ' .$cliente["nome"],
            'selectValues' => $selectValues,
        ]);
    }

    public function elimina($id)
    {
        $this->ClientiModel->delete($id);
        // Redirect o mostra un messaggio di successo
        return redirect()->back()->with('success', 'Cliente eliminato con successo.');
    }
    public function salva($id = null)
    {
        log_message('info', 'ClientiController::salva - ');
        log_message('info', 'ID ricevuto ' . $id);
        $data = $this->request->getPost();

        if ($id == null) {
            log_message('info', 'ID nullo ' . $id);
            $clienteID = $this->ClientiModel->salva($data);
        } else {
            $data['id'] = $clienteID = $id; // Aggiungo l'ID per la modifica
            log_message('info', 'ID NON nullo ' . $id);
            $this->ClientiModel->salva($data);
        }
        log_message('info', 'Ricevo questi dati nel CONTROLLER: ' . print_r($data, true) . ' - ClienteID: ' . $clienteID);
        // Redirect o mostra un messaggio di successo
        return redirect()->to('clienti/schedaCliente/' . $clienteID)->with('success', 'Cliente salvato con successo.');

    }
    /*public function __clientiFilters()
    {
        $tipoLicenza = $this->request->getPost('tipoLicenza');
        echo "Tipo licenza selezionato: " . $tipoLicenza;
        if ($tipoLicenza) {
        }
        return view('clienti/form', [
            'mode' => 'view',
            'cliente' => $cliente,
            'action' => '',
            'title' => 'Dettagli Cliente ' . esc($cliente->nome),
        ]);
    }*/

    public function countLicenzeByCliente()
    {
        $rows =  $this->LicenzeModel
            ->select('clienti_id, COUNT(id) AS numLicenze')
            ->groupBy('clienti_id')
            ->findAll();
        $result = array_column($rows, 'numLicenze', 'clienti_id');
        //dd($result);
        log_message('debug', 'LicenzeModel class: ' . get_class($this->LicenzeModel));
        log_message('debug', 'LicenzeModel parent: ' . get_parent_class($this->LicenzeModel));
        log_message('debug', 'afterFind: ' . json_encode($this->LicenzeModel->afterFind ?? null));
        return $result;
    }
    public function getTipoLicenzeByCliente()
    {
        $rows = $this->LicenzeModel->select('clienti_id, tipo')
            ->groupBy('clienti_id, tipo')
            ->get()
            ->getResultArray(); // array normale, nessuna indicizzazione su PK come in findAll()
        // Estraggo un array associativo con clienti_id come chiave e tipo come valore 
        $result = [];
        foreach ($rows as $row) {
            $result[$row['clienti_id']][] = $row['tipo'];
        }
        //log_message('info', 'tipoLicenzaPerCliente: ' . print_r($result, true));
        return $result;
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
