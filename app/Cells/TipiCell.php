<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use App\Models\TipiLicenzeModel;

class TipiCell extends Cell
{
    public function filtro(): string
    {
        $tipi= (new TipiLicenzeModel())->select('tipo, categoria')->distinct()->findAll();
        //dd($tipi);
        return $this->view('tipi_filtro', ['tipi' => $tipi]);
    }

    public function select(?int $selezionato = null): string
    {
        $tipi = (new TipiLicenzeModel())->select('id, tipo, modello, categoria')->findAll();
        // Raggruppa per tipo per costruire gli optgroup (es. "Sigla" → [Start, Ultimate, Cloud])
        $gruppi = [];
        foreach ($tipi as $t) {
            $gruppi[$t['categoria_label']][] = $t;
            }
        //dd($gruppi);

        return $this->view('tipi_select', ['gruppi' => $gruppi, 'selezionato' => $selezionato]);
    }

    /**
     * Select per versioni: value = nome tipo (stringa), non FK numerica.
     */
    public function tipoNomiSelect(?string $selezionato = null): string
    {
        $tipi = (new TipiLicenzeModel())->select('tipo')->distinct()->orderBy('tipo')->findAll();
        return $this->view('tipi_nomi_select', ['tipi' => $tipi, 'selezionato' => $selezionato]);
    }
}
