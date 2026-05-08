<?php

namespace App\Models\Import;

use CodeIgniter\Model;

class ExternalModel extends Model
{
    protected $DBGroup          = 'external';
    protected $table            = 'nrg.v_tbcf_tbana';
    protected $primaryKey       = 'tbana_id_pk';
    protected $returnType       = 'array';


    /**
     * Genera l'elenco delle anagrafiche in base al tipo ricevuto dalla libreria
     */
    public function getExternal(string $tipo): array
    {
        return $this->select()
            ->where('tbcf_tp', $tipo)
            ->orderBy('tbana_ragsoc1', 'ASC')
            ->findAll();
    }
    
    /*public function getClientiExternal()
    {
        return $this->select()
            ->where('tbcf_tp', 'C')
            ->orderBy('tbana_ragsoc1', 'ASC')
            ->findAll();
    }*/
}
