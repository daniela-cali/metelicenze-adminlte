<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\TipiLicenzeModel;

class TipiCell extends Cell
{
    public mixed $selezionato = null;
    public ?string $selezionatoNome = null;

    public function filtro(): string
    {
        $tipi = (new TipiLicenzeModel())->select('tipo, categoria')->distinct()->findAll();
        return $this->view('tipi_filtro', ['tipi' => $tipi]);
    }

    public function select(): string
    {
        $tipi = (new TipiLicenzeModel())->select('id, tipo, modello, categoria')->findAll();
        $gruppi = [];
        foreach ($tipi as $t) {
            $gruppi[$t['categoria_label']][] = $t;
        }
        return $this->view('tipi_select', ['gruppi' => $gruppi, 'selezionato' => $this->selezionato]);
    }

    public function tipoNomiSelect(): string
    {
        $tipi = (new TipiLicenzeModel())->select('tipo')->distinct()->orderBy('tipo')->findAll();
        return $this->view('tipi_nomi_select', ['tipi' => $tipi, 'selezionato' => $this->selezionatoNome]);
    }
}
