<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientiExternalModel extends Model
{
    protected $DBGroup          = 'external';
    protected $transcoding = true; // Abilita la transcodifica dei campi
        protected $transcodingArray = [
        'tbana_id_pk' => 'id_external',
        'tbcf_cd' => 'codice',
        'tbana_ragsoc1' => 'nome',
        'tbana_piva' => 'piva',
        'tbana_indirizzo1' => 'indirizzo',
        'tbana_citta' => 'citta',
        'tbana_cap' => 'cap',
        'tbana_provincia' => 'provincia',
        'tbana_telefono1' => 'telefono',
        'tbana_email' => 'email'
    ];

    protected $transcodedFields = '';

    protected $table            = 'nrg.v_tbcf_tbana';
    protected $primaryKey       = 'tbana_id_pk';

    protected $returnType       = 'array';

    /**
     * Inizializza il modello e prepara i campi transcodificati
     */
    protected function initialize()
    {
        if ($this->transcoding) {
            $fields = [];
            foreach ($this->transcodingArray as $dbField => $alias) {
                $fields[] = "$dbField as $alias";
            }
            $this->transcodedFields = implode(', ', $fields);
        }
    }

    /**
     * Genera l'elenco di tutti i clienti
     */

    public function getTranscodedClienti()
    {
        return $this->select($this->transcodedFields)
            ->orderBy('tbana_ragsoc1', 'ASC')
            ->where('tbcf_tp', 'C')
            ->findAll();
    }

}
