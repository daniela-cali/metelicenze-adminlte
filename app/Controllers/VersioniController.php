<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VersioniModel;

class VersioniController extends BaseController
{
    protected $VersioniModel;

    public function __construct()
    {
        $this->VersioniModel = new VersioniModel();
    }

    public function index(): string
    {
        $data = [
            'versioni' => $this->VersioniModel->getVersioni(),
            'title'    => 'Elenco Versioni',
        ];
        return $this->view('versioni/index', $data);
    }

    public function show($id)
    {
        $versione = $this->VersioniModel->getVersioneById($id);
        $data = [
            'mode'    => 'view',
            'versione' => $versione,
            'title'   => 'Versione ' . esc($versione['codice']),
            'backTo'  => $this->getBackTo(url_to('versioni_index')),
            'form'    => [
                'action'     => '',
                'method'     => 'get',
                'spoof'      => null,
                'submitText' => '',
                'readonly'   => true,
            ],
        ];
        return $this->view('versioni/form', $data);
    }

    public function create()
    {
        $data = [
            'mode'    => 'create',
            'versione' => null,
            'title'   => 'Crea Nuova Versione',
            'backTo'  => $this->getBackTo(url_to('versioni_index')),
            'form'    => [
                'action'     => url_to('versioni_store'),
                'method'     => 'POST',
                'spoof'      => null,
                'submitText' => 'Salva',
                'readonly'   => false,
            ],
        ];
        return $this->view('versioni/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        if (!$data) {
            return redirect()->back()->with('error', 'Dati mancanti per creare la versione.');
        }
        // Se questa versione è marcata come "ultima", azzero il flag sulle altre dello stesso tipo
        if (!empty($data['ultima'])) {
            $this->VersioniModel->set(['ultima' => 0])->where('ultima', 1)->where('tipo', $data['tipo'])->update();
        }
        if ($this->VersioniModel->save($data)) {
            return redirect()->to($this->getBackTo(url_to('versioni_index')))
                ->with('success', 'Versione creata con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante la creazione della versione.')->withInput();
        }
    }

    public function edit($id)
    {
        $versione = $this->VersioniModel->find($id);
        if (!$versione) {
            return redirect()->to(url_to('versioni_index'))->with('error', 'Versione non trovata.');
        }
        $data = [
            'mode'    => 'edit',
            'versione' => $versione,
            'title'   => 'Modifica Versione ' . esc($versione['codice']),
            'backTo'  => $this->getBackTo(url_to('versioni_index')),
            'form'    => [
                'action'     => url_to('versioni_update', $id),
                'method'     => 'POST',
                'spoof'      => 'PUT',
                'submitText' => 'Aggiorna',
                'readonly'   => false,
            ],
        ];
        return $this->view('versioni/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;
        // Se questa versione è marcata come "ultima", azzero il flag sulle altre dello stesso tipo
        if (!empty($data['ultima'])) {
            $this->VersioniModel->set(['ultima' => 0])->where('ultima', 1)->where('tipo', $data['tipo'])->update();
        }
        if ($this->VersioniModel->save($data)) {
            return redirect()->to($this->getBackTo(url_to('versioni_index')))
                ->with('success', 'Versione aggiornata con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento della versione.')->withInput();
        }
    }

    public function delete($id)
    {
        if ($this->VersioniModel->delete($id)) {
            return redirect()->to($this->getBackTo(url_to('versioni_index')))
                ->with('success', 'Versione eliminata con successo.');
        } else {
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione della versione.');
        }
    }
}
