<?php

namespace App\Controllers\Import;

use App\Controllers\BaseController;
use App\Libraries\Import\ImportService;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Import\TranscodificheModel;

helper('db_status');

class ImportController extends BaseController
{
    public function index()
    {
        $transcodificheModel = new TranscodificheModel();
        $tables = $transcodificheModel->distinct()->select('tabella_dest')->findAll();
        $nullCounts = []; //Contatore contenuto per ogni tabella
        foreach ($tables as $key => $table) {
            $nullCounts[$table["tabella_dest"]] = $transcodificheModel
                ->where('tabella_dest', $table["tabella_dest"])
                ->where('campo_ori IS NULL')
                ->countAllResults();
        }
        //dd($tables);
        return $this->view('import/index', [
            'tables' => $tables,
            'nullCounts' => $nullCounts
        ]);
    }

    public function loadTablesFields()
    {
        $importService = new ImportService();
        try {
            $message = $importService->loadTablesFields();
            session()->setFlashdata('success', $message);
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e);
        }
        return redirect()->to(url_to('import_index'));
    }

    public function uploadCsv()
    {
        $importService = new ImportService();
        $file = $this->request->getFile('uploadedFile');
        $tipoImport = $this->request->getPost('tipo');
        $columName = $this->request->getPost('columnName');
        $tabella = $this->request->getPost('tabella');
        $transcodificheModel = new TranscodificheModel();

        try {
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move(WRITEPATH . 'uploads/');
                $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $file->getName();
                if ($tipoImport === 'importa_colonne') {
                    $fields = $importService->getCsvFields($path, $columName);
                    $campiInterni = $transcodificheModel->where('tabella_dest', $tabella)->findAll();
                    return $this->view('import/associazione', [
                        'campiEsterni' => $fields,
                        'tabella'      => $tabella,
                        'campiInterni' => $campiInterni,
                    ]);
                } 
                if ($tipoImport === 'importa_file') {
                    $message = $importService->import($tabella, 'csv', $path);
                    session()->setFlashdata('success', $message);
                    return redirect()->to(url_to('clienti_index'));
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            log_message('error', $e->getMessage());
            $message = "Inserire un file .cvs valido.";
            session()->setFlashdata('error', $message);
            return redirect()->to(url_to('import_index'));
        }
    }

    public function storeMapping(){
        $transcodificheModel = new TranscodificheModel();
        $tabella = $this->request->getPost('tabella');
        $mapping = $this->request->getPost('mapping');
        foreach ($mapping as $campoDest => $campoOri) {
            $transcodificheModel
            ->where('tabella_dest', $tabella)
            ->where('campo_dest', $campoDest)
            ->set(['campo_ori'=>  $campoOri, 'campo_dest'=> $campoDest])
            ->update();
        }
        session()->setFlashdata('success', 'Mappatura salvata con successo');
        return redirect()->to(url_to('import_index'));
    }

    public function fromDatabase($table){
        /* Verifico che il database esterno sia raggiungibile prima di tentare la connessione,
         * evitando il timeout/errore grezzo di CodeIgniter in caso di server irraggiungibile */
        if (!db_is_available('external')) {
            session()->setFlashdata('error', 'Database esterno non raggiungibile. Verificare la connessione e riprovare.');
            return redirect()->to(url_to('import_index'));
        }
        $importService = new ImportService();
        try {
            $message = $importService->import($table, 'database');
            session()->setFlashdata('success', $message);
            return redirect()->to(url_to('clienti_index'));
        } catch (\Exception $e) {
            log_message('error', 'Errore importazione da database: ' . $e->getMessage());
            session()->setFlashdata('error', 'Errore durante l\'importazione: ' . $e->getMessage());
            return redirect()->to(url_to('import_index'));
        }
    }
}
