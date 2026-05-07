<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends BaseController
{
    public function getLicenzeByTipo(string $tipo)
    {
        $model = new \App\Models\ClientiModel();
        $result = $model->getClientiByTipoLicenza($tipo);
        return $this->view('test/test', [
            'test' => $result
        ]);

    }

    public function tipi_filtro()
    {
        $cell = new \App\Cells\TipiCell();
        $result = $cell->filtro();
        return $this->view('test/test', [
            'test' => $result
        ]);

    }

    public function tipi_select()
    {
        $cell = new \App\Cells\TipiCell();
        $result = $cell->select();
        //dd($result);
        return $this->view('test/test', [
            'test' => $result
        ]);

    }

}
