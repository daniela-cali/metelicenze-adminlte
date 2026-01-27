<?php

namespace App\Models;

class VersioniModel extends AuditModel
{
    protected $table            = 'versioni';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'codice',
        'release',
        'note_versione',
        'dt_rilascio',
        'stato',
        'ultima',
        'tipo',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
];



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
        return $this->select(['id', 'codice', 'dt_rilascio','release'])->where('tipo', $tipo)->orderBy('dt_rilascio', 'DESC')
            ->findAll();    
    }

    public function getVersioneById($idVersione)
    {
        return $this->orderBy('dt_rilascio', 'DESC')
            ->where('id', $idVersione)
            ->first();    
    }


}
