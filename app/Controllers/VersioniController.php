<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class VersioniController extends BaseController
{
    protected $VersioniModel;
    public function __construct()
    {
        $this->VersioniModel = new \App\Models\VersioniModel();
    }

    public function index()
    {
        $data['versioni'] = $this->VersioniModel->getVersioni();
        $data['title'] = 'Elenco Versioni';

        return view('versioni/index', $data);
    }

    public function visualizza($idVersione)
    {
        $versione = $this->VersioniModel->getVersioneById($idVersione);
        return view('versioni/form', [
            'mode' => 'view',
            'action' => '', // Nessuna azione in visualizzazione
            'versione' => $versione, // Non abbiamo una versione esistente da modificare
            'title' => 'Dettagli Versione ' . esc($versione->codice),
        ]);
    }

    public function crea()
    {
        return view('versioni/form', [
            'mode' => 'create',
            'action' => base_url('/versioni/salva'), // Non ha ancora ID
            'versione' => null, // Non abbiamo una versione esistente da modificare
            'title' => 'Crea Nuova Versione',
        ]);
    }

    public function modifica($idVersione)
    {
        $versione = $this->VersioniModel->find($idVersione);
        if (!$versione) {
            return redirect()->to('/versioni')->with('error', 'Versione non trovata.');
        }

        return view('versioni/form', [
            'mode' => 'edit',
            'action' => base_url('/versioni/salva/' . $idVersione),
            'versione' => $versione,
            'title' => 'Modifica Versione ' . esc($versione->codice),
        ]);
    }

    public function salva($idVersione = null)
    {
        // Logica per salvare la versione
        // Se $idVersione è null, stiamo creando una nuova versione
        // Altrimenti, stiamo modificando una versione esistente
        $data = $this->request->getPost();

        $ultima_sn = $data['ultima'] ?? false;
        $tipo = $data['tipo'] ?? false;
        if ($ultima_sn) {
            // Se l'utente ha selezionato "Ultima", aggiorno tutte le altre versioni dello stesso tipo
            $this->VersioniModel->set(['ultima' => 0])->where('ultima', 1)->where('tipo', $tipo)->update();
        }
        log_message('info', 'Dati ricevuti per il salvataggio: ' . print_r($data, true));
        //echo "Dati ricevuti: ";
        //print_r($data); 
        $this->VersioniModel->save($data);
        if ($this->VersioniModel->errors()) {
            // Se ci sono errori, li mostriamo
            return redirect()->back()->withInput()->with('errors', $this->VersioniModel->errors());
        }
        // Se non ci sono errori, reindirizziamo alla lista delle versioni
        return redirect()->to('/versioni')->with('success', 'Versione salvata con successo!');
    }
    public function elimina($idVersione)
    {
        $versione = $this->VersioniModel->find($idVersione);
        if (!$versione) {
            return redirect()->to('/versioni')->with('error', 'Versione non trovata.');
        }

        $this->VersioniModel->delete($idVersione);
        return redirect()->to('/versioni')->with('success', 'Versione eliminata con successo.');
    }
}
