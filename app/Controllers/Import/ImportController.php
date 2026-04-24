<?php

namespace App\Controllers\Import;

use App\Controllers\BaseController;
use App\Libraries\Import\ImportService;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Import\TranscodificheModel;

class ImportController extends BaseController
{
    public function index()
    {
        $transcodificheModel = new TranscodificheModel();
        $tables = $transcodificheModel->distinct()->select('tabella_dest')->findAll();        
        return $this->view('import/index', ['tables'=> $tables]);
    }

    public function loadTablesFields()
    {
        $importService = new ImportService();
        return $importService->loadTablesFields();
    }
    
}
