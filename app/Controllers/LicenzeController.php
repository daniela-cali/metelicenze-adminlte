<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

helper(['array']);
class LicenzeController extends BaseController
{
    protected $LicenzeModel;
    protected $AggiornamentiModel;
    protected $ClientiModel;
    public function __construct()
    {
        $this->LicenzeModel = new \App\Models\LicenzeModel();
        $this->AggiornamentiModel = new \App\Models\AggiornamentiModel();
        $this->ClientiModel = new \App\Models\ClientiModel();
    }

    public function index()
    {

        $licenze = $this->LicenzeModel->getLicenze();
        $clienti = $this->ClientiModel->getInfoClienti();
        $aggiornamenti = $this->AggiornamentiModel->getLastAggiornamenti();
        foreach ($licenze as &$licenza) {
            // Trova il cliente corrispondente per ogni licenza
            $cliente = array_find($clienti, fn($c) => $c["id"] === $licenza["clienti_id"]);
            /*Vado a recuperare l'id del padre che normalmente è la licenza stessa, ma per i figli fa riferimento al padre direttamente, 
            in modo da vedere il corretto stato della licenza*/
            //dd($aggiornamenti);
            $ultimo_agg = array_find($aggiornamenti, fn($a) => $a["licenza_id"] === $licenza["padre_lic_id"]);
            $licenza["clienteNome"] = $cliente ? $cliente["nome"] : 'Cliente non trovato';
            $licenza["clienteId"] = $cliente ? $cliente["id"] : null;
            //$licenza->ultimoAggiornamento = $ultimo_agg ? array_values($ultimo_agg)->ultimo_aggiornamento : 'N/A';
            if ($ultimo_agg) {
                //dd($ultimo_agg);
                $ultimo_agg_data = $ultimo_agg["ultimo_aggiornamento"];
                //Formatto la data in d/m/Y e la tolgo dalla view per mostrare anche N/A altrimenti esce 01/01/1970
                $licenza["ultimoAggiornamento"] = date('d/m/Y', strtotime($ultimo_agg_data));
                $licenza["ultimaVersione"] = $ultimo_agg["ultima"] ? true : false;
            } else {
                $licenza["ultimaVersione"] = false;
                $licenza["ultimoAggiornamento"] = 'N/A';
            }
            $licenza["versioneUltimoAggiornamento"] = $ultimo_agg ? $ultimo_agg["versione_codice"] : 'N/A';
            /**
             * Commento in quanto ho cambiato il tipo in enum nel database
             */
            //$licenza["tipo"] = decodingTipo($licenza["tipo"]);
            //$licenza->modello = decodingModello($licenza->modello);
        }
        $data = [
            'licenze' => $licenze,
            'title' => 'Elenco Licenze'
        ];

        return $this->view('licenze/index', $data);
    }

    public function show($id)
    {
        // Logica per visualizzare i dettagli di una licenza
        $licenza = $this->LicenzeModel->getLicenzeById($id);
        $backTo = $this->getBackTo(base_url('/licenze'));
        $cliente_nome = $this->ClientiModel->select('nome')->where('id', $licenza["clienti_id"])->first()['nome'];

        $data = [
            'mode' => 'view',
            'licenza' => $licenza,
            'action' => '',
            'title' => 'Licenza ' . esc($licenza["codice"]) . ' - ' . esc($cliente_nome),
            'backTo' => $backTo,
        ];
        return $this->view('licenze/form', $data);
    }

    public function create($idCliente = null)
    {

        /** 
         * Se non è fornito un ID cliente, non posso salvare la licenza
         * Pertanto si può creare una licenza solo dalla scheda cliente
         * */

        //log_message('info', 'LicenzeController::crea - Padre corrente dalla sessione ID: ' . $padre_id);

        if ($idCliente === null) {
            return redirect()->back()->with('error', 'Selezionare un cliente!.');
        }

        $cliente = $this->ClientiModel->getClientiById($idCliente);
        $padre_id = $cliente["padre_id"];
        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente non trovato!.');
        } elseif ($cliente["padre_id"]) {
            log_message('info', 'LicenzeController::crea Cliente selezionato è un figlio, prendo il padre ID: ' . $cliente["padre_id"]);
            // Se è figlio allora prendo le licenze del padre messe in un array con 3 elementi (Sigla, VariHub, SKNT) per poterle mostrare nel form
            $licenzePadre = $this->LicenzeModel->getLicenzeByCliente($cliente["padre_id"]);
            //log_message('info', 'LicenzeController::crea Licenze del padre trovate: ' . print_r($licenzePadre, true));
            foreach ($licenzePadre as $licenza) {
                if ($licenza["tipo"] === 'Sigla') {
                    $padreLic['licenzePadre']['Sigla'] = $licenza;
                } elseif ($licenza["tipo"] === 'VarHub') {
                    $padreLic['licenzePadre']['VarHub'] = $licenza;
                } elseif ($licenza["tipo"] === 'SKNT') {
                    $padreLic['licenzePadre']['SKNT'] = $licenza;
                }
            }
            log_message('info', 'LicenzeController::crea Licenze del padre organizzate: ' . print_r($padreLic, true));

        }
        $data = [
            'title' => 'Crea Licenza per Cliente ' . esc($cliente["nome"]) . ' [ID: ' . esc($idCliente) . ']',
            'mode' => 'create',
            'licenza' => null,
            'licenzePadre' => $padreLic['licenzePadre'] ?? [], // Passo le licenze del padre se esistono
            'id_cliente' => $idCliente,
            'backTo' => $this->getBackTo(url_to('clienti_index')),
            'form' => [
                'action' => url_to('clienti_show', $idCliente), // Dopo la creazione torno alla scheda del cliente
                'method' => 'post',
                'spoof' => null,
                'submitText' => 'Salva',
                'readonly' => false,

            ]
        ];
        /*$data = [
            'mode' => 'create',
            'action' => url_to('licenze_store'),
            'id_cliente' => $idCliente,
            'cliente' => $cliente,
            'licenza' => null,
            'title' => 
            'backTo' => $this->getBackTo(url_to('licenze_index')),
        ];*/

        log_message('info', 'LicenzeController::crea - Creazione licenza per Cliente ID: ' . $idCliente . ' con questi dati inviati alla view: ' . print_r($data, true));


        return $this->view('licenze/form', $data);
    }
    public function store()
    {
        $data = $this->request->getPost();
        if (!$data) {
            return redirect()->back()->with('error', 'Dati mancanti per creare la licenza.');
        }
        if ($this->LicenzeModel->save($data)) {
            $licenzaID = $this->LicenzeModel->getInsertID();
            return redirect()->to(
                $this->getBackTo(url_to('licenze_show', $licenzaID))
            )->with('success', 'Licenza creata con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione della licenza.')->withInput();
        }
    }

    public function edit($id)
    {
        // Logica per modificare una licenza

        $licenza = $this->LicenzeModel->getLicenzeById($id);
        $idCliente = $licenza["clienti_id"]; // Ottengo l'ID del cliente associato alla licenza
        $codice =  $licenza["codice"];
        //$backTo = 
        $data = [
            'licenza' => $licenza,
            'id_cliente' => $idCliente,
            'mode' => 'edit',
            'title' => 'Modifica Licenza ' . esc($codice) . ' (ID: ' . esc($id) . ')',
            'action' => url_to('licenze_update', $id),
            'backTo' => $this->getBackTo(url_to('licenze_index')),
        ];

        return $this->view('licenze/form', $data);

        // Redirect o mostra un messaggio di successo
        return redirect()->to(url_to('clienti_show', ['id' => $idCliente]))
            ->with('success', 'Licenza modificata con successo.');
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id; // Aggiungo l'ID per la modifica
        if ($this->LicenzeModel->save($data)) {
            return redirect()->to(
                $this->getBackTo(url_to('licenze_show', $id))
            )->with('success', 'Licenza aggiornata con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento della licenza.')->withInput();
        }
    }

    public function delete($id)
    {
        // Logica per eliminare una licenza
        $this->LicenzeModel->delete($id);
        // Redirect o mostra un messaggio di successo
        return redirect()->to($this->getBackTo(url_to('licenze_index')))
            ->with('success', 'Licenza eliminata con successo.');
    }
}
