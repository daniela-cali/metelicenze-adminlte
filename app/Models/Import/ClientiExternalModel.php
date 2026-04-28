<?php

namespace App\Models\Import;

use CodeIgniter\Model;

class ClientiExternalModel extends Model
{
    protected $DBGroup          = 'external';

    protected $table            = 'nrg.v_tbcf_tbana';
    protected $primaryKey       = 'tbana_id_pk';

    protected $returnType       = 'array';


    /**
     * Genera l'elenco di tutti i clienti
     */
    public function getClientiExternal()
    {
        return $this->select()
            ->where('tbcf_tp', 'C')
            ->orderBy('tbana_ragsoc1', 'ASC')
            ->findAll();
    }
}
