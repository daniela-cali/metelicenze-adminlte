<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
helper('decoding');
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
        foreach ($licenze as $licenza) {
            // Trova il cliente corrispondente per ogni licenza
            $cliente = array_filter($clienti, fn($c) => $c->id === $licenza->clienti_id);
            /*Vado a recuperare l'id del padre che normalmente è la licenza stessa, ma per i figli fa riferimento al padre direttamente, 
            in modo da vedere il corretto stato della licenza*/
            $ultimo_agg = array_filter($aggiornamenti, fn($a) => $a->licenza_id === $licenza->padre_lic_id);

            $licenza->clienteNome = $cliente ? array_values($cliente)[0]->nome : 'Cliente non trovato';
            $licenza->clienteId = $cliente ? array_values($cliente)[0]->id : null;           
            //$licenza->ultimoAggiornamento = $ultimo_agg ? array_values($ultimo_agg)[0]->ultimo_aggiornamento : 'N/A';
            if ($ultimo_agg) {
                $ultimo_agg_data = array_values($ultimo_agg)[0]->ultimo_aggiornamento;
                //Formatto la data in d/m/Y e la tolgo dalla view per mostrare anche N/A altrimenti esce 01/01/1970
                $licenza->ultimoAggiornamento = date('d/m/Y', strtotime($ultimo_agg_data));
                $licenza->ultimaVersione = array_values($ultimo_agg)[0]->ultima ? true : false;
            } else {
                $licenza->ultimaVersione = false;
                $licenza->ultimoAggiornamento = 'N/A';
            }
            $licenza->versioneUltimoAggiornamento = $ultimo_agg ? array_values($ultimo_agg)[0]->versione_codice : 'N/A';          
            /**
             * Commento in quanto ho cambiato il tipo in enum nel database
             */
            //$licenza->tipo = decodingTipo($licenza->tipo);
            //$licenza->modello = decodingModello($licenza->modello);
        }
        $data['licenze'] = $licenze;
        $data['title'] = 'Elenco Licenze';

        return view('licenze/index', $data);
    }


    public function visualizza($idLicenza)
    {
        // Logica per visualizzare i dettagli di una licenza
        $licenza = $this->LicenzeModel->getLicenzeById($idLicenza);

        return view('licenze/form', [
            'mode' => 'view',
            'licenza' => $licenza,
            'action' => '',
            'title' => 'Dettagli Licenza ' . esc($licenza->codice),
        ]);
    }

    public function crea($idCliente = null)
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
        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente non trovato!.');
        } elseif ($cliente->padre_id) {
            log_message('info', 'LicenzeController::crea Cliente selezionato è un figlio, prendo il padre ID: ' . $cliente->padre_id);
            // Se è figlio allora prendo le licenze del padre messe in un array con 3 elementi (Sigla, VariHub, SKNT) per poterle mostrare nel form
            $licenzePadre = $this->LicenzeModel->getLicenzeByCliente($cliente->padre_id);
            //log_message('info', 'LicenzeController::crea Licenze del padre trovate: ' . print_r($licenzePadre, true));
            foreach ($licenzePadre as $licenza) {
                if ($licenza->tipo === 'Sigla') {
                    $data['licenzePadre']['Sigla'] = $licenza;
                } elseif ($licenza->tipo === 'VarHub') {
                    $data['licenzePadre']['VarHub'] = $licenza;
                } elseif ($licenza->tipo === 'SKNT') {
                    $data['licenzePadre']['SKNT'] = $licenza;
                }
            }
            //log_message('info', 'LicenzeController::crea Licenze del padre organizzate: ' . print_r($data, true));

        }


        $data['mode'] = 'create';
        $data['action'] = base_url('/licenze/salva/' . $idCliente); //Essendo nel crea la licenza non ha ancora ID
        $data['id_cliente'] = $idCliente;
        $data['cliente'] = $cliente;
        $data['licenza'] = null; 
        $data['title'] = 'Crea Licenza per Cliente ' . $data['cliente']->nome . ' [ID: ' . $idCliente . ']';
        log_message('info', 'LicenzeController::crea - Creazione licenza per Cliente ID: ' . $idCliente . ' con questi dati inviati alla view: ' . print_r($data, true));


        return view('licenze/form', $data);
    }

    public function modifica($idLicenza)
    {
        // Logica per modificare una licenza


        $licenza = $this->LicenzeModel->getLicenzeById($idLicenza);
        $idCliente = $licenza->clienti_id; // Ottengo l'ID del cliente associato alla licenza
        $codice =  $licenza->codice;
        //$backTo = 
        $data = [
            'licenza' => $licenza,
            'id_cliente' => $idCliente,
            'mode' => 'edit',
            'title' => 'Modifica Licenza ' . esc($codice) . ' (ID: ' . esc($idLicenza) . ')',
            'action' => base_url('/licenze/salva/' . $idCliente . '/' . $idLicenza),
        ];

        return view('licenze/form', [
            'mode' => 'edit',
            'licenza' => $licenza,
            'id_cliente' => $idCliente,
            'action' => base_url('/licenze/salva/' . $idCliente . '/' . $idLicenza), // Passo l'ID della licenza per la modifica
            'title' => 'Modifica Licenza ' . esc($codice) . ' (ID: ' . esc($idLicenza) . ')',
        ]);

        // Redirect o mostra un messaggio di successo
        return redirect()->to('clienti/schedaCliente/' . $idCliente);
    }
    
    /**
     * 
     * Salva una licenza, sia che sia nuova o modificata
     * @param int|null $idLicenza ID della licenza da modificare o creare
     */
    public function salva($idCliente = null, $idLicenza = null)
    {
        // Se non è fornito un ID cliente, non posso salvare la licenza
        $data = $this->request->getPost(); // Prende tutti i campi del form
        log_message('info', 'Ricevo questi dati nel CONTROLLER: ' . print_r($data, true));

        if ($idCliente === null) {
            return redirect()->back()->with('error', 'Selezionare un cliente!.');
        } else {
            $data['clienti_id'] = $idCliente; // Associa la licenza al cliente
        }
        //Imposto il padre della licenza come quello selezionato nel form, altrimenti se nullo lo imposto come la licenza stessa
        $data['padre_lic_id'] = $data['padre_lic_id'] !== null ? $data['padre_lic_id'] : $idLicenza;
        if ($idLicenza !== null) {
            $data['id'] = $idLicenza; // Se sto modificando, aggiungo l'ID della licenza
        }
        if ($data['tipo'] === 'VarHub') {
            // Per VarHub, imposto codice = ad ambiente
            $data['codice'] = $data['ambiente'];
        }
        log_message('info', 'Ricevo questo idcliente: ' . $idCliente . ' e idlicenza: ' . $idLicenza);
        log_message('info', 'Data contiene questo prima di inviare al model ->salva: ' . print_r($data, true));

        // Salvo la licenza nel database
        $this->LicenzeModel->salva($data);

        // Redirect o mostra un messaggio di successo
        return redirect()->to('clienti/schedaCliente/' . $idCliente)->with('success', 'Licenza salvata con successo.');
    }

    public function elimina($idLicenza)
    {
        // Logica per eliminare una licenza
        $this->LicenzeModel->delete($idLicenza);
        // Redirect o mostra un messaggio di successo
        return redirect()->back()->with('success', 'Licenza eliminata con successo.');
    }


}
