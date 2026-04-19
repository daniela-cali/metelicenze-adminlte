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

    /**
     * Recupera tutte le versioni di un tipo specifico
     */

    public function getVersioniByTipo($tipo)
    {
        return $this->select(['id', 'codice', 'dt_rilascio', 'release'])->where('tipo', $tipo)->orderBy('dt_rilascio', 'DESC')
            ->findAll();
    }

    /**
     * Recupera una versione specifica per ID
     *  */
    public function getVersioneById($idVersione)
    {
        return $this->where('id', $idVersione)
            ->first();
    }

    /**
     * Recupera solo le versioni contrassegnate come "ultima" distincte per tipo
     */
    public function getUltimeVersioni()
    {
        return $this->distinct()->where('ultima', 1)->findAll();
    }

}
