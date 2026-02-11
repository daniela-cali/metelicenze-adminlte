<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientiImportModel;

helper('db_status');

class ImportClientiController extends BaseController
{
    public function index()
    {
        $connectionStatus = db_is_available('external');
        //dd($connectionStatus);
        if ($connectionStatus) {
            $data['title'] = 'Importa Clienti';
            $data['connectionStatus'] = $connectionStatus;
            $clientiModel = new ClientiImportModel();
            $data['clienti'] = $clientiModel->getRecordsetForImport();
            log_message('info', 'Clienti per importazione: ' . print_r($data['clienti'], true));
            return view('admin/importClienti', $data);
        } else {
            //$data['title'] = 'Connessione al DB External non riuscita';
            $data['connectionStatus'] = $connectionStatus;
            
            return view('admin/importClienti_down', $data);
        }
    }
    public function importClienti()
    {
        $clientiModel = new ClientiImportModel();
        $importedMessage = $clientiModel->importClienti();

        return redirect()->to('/admin/import_clienti')->with('success', $importedMessage);
    }
}
