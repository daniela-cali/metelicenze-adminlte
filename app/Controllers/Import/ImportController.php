<?php

namespace App\Controllers\Import;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;


class ImportController extends BaseController
{
    public function index()
    {
        return $this->view('import/index');
    }
    
}
