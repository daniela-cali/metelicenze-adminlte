<?php

namespace App\Models;

use CodeIgniter\Model;

class VersioniModel extends Model
{
    protected $table            = 'versioni';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'codice',
        'release',
        'note_versione',
        'dt_rilascio',
        'stato',
        'ultima',
        'tipo'
];

    protected bool $allowEmptyInserts = false;


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Recupera tutte le versioni
     */
    public function getVersioni()
    {
        return $this->orderBy('dt_rilascio', 'DESC')
            ->findAll();    
    }

    public function getVersioniByTipo($tipo)
    {
        return $this->where('tipo', $tipo)->orderBy('dt_rilascio', 'DESC')
            ->findAll();    
    }

    public function getVersioneById($idVersione)
    {
        return $this->orderBy('dt_rilascio', 'DESC')
            ->where('id', $idVersione)
            ->first();    
    }


}
