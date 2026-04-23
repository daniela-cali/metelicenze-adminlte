<?php

namespace App\Controllers\Import;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Import\ClientiImportModel;

helper('db_status');

class ImportClientiController extends BaseController
{
    public function fromDatabase()
    {
        $connectionStatus = db_is_available('external');
        //dd($connectionStatus);
        if ($connectionStatus) {
            $data['title'] = 'Importa Clienti';
            $data['connectionStatus'] = $connectionStatus;
            $clientiModel = new ClientiImportModel();
            $data['clienti'] = $clientiModel->getRecordsetForImport();
            log_message('info', 'Clienti per importazione: ' . print_r($data['clienti'], true));
            return $this->view('import/clienti/importClienti', $data);
        } else {
            //$data['title'] = 'Connessione al DB External non riuscita';
            $data['connectionStatus'] = $connectionStatus;
            
            return $this->view('import/clienti/importClienti_down', $data);
        }
    }
    public function fromCSV()
    {
        $data['title'] = 'Importa Clienti da CSV';
        return $this->view('/import/under_construction', $data);

    }
    public function create()
    {
        $data['title'] = 'Crea Cliente Manualmente';
        return $this->view('/import/under_construction', $data);
    }

}
