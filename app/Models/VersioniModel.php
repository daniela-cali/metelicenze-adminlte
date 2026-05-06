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
     * Restituisce una riga per ogni tipo distinto in tipilicenze,
     * con la versione più recente se esiste (NULL se non ancora presente).
     */
    public function getUltimeVersioni(): array
    {
        $subTipi = $this->db->table('tipilicenze')
            ->distinct()
            ->select('tipo')
            ->getCompiledSelect();

        $subLatest = $this->db->table('versioni')
            ->select('tipo, MAX(id) AS max_id')
            ->where('deleted_at IS NULL')
            ->groupBy('tipo')
            ->getCompiledSelect();

        return $this->db->query("
            SELECT tipi.tipo, v.codice, v.dt_rilascio, v.release, v.ultima
            FROM ($subTipi) tipi
            LEFT JOIN ($subLatest) latest ON tipi.tipo = latest.tipo
            LEFT JOIN versioni v ON v.id = latest.max_id
            ORDER BY tipi.tipo ASC
        ")->getResultArray();
    }

}
