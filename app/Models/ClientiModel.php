<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientiModel extends AuditModel
{
    protected $table            = 'clienti';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'codice',
        'nome',
        'piva',
        'indirizzo',
        'citta',
        'cap',
        'provincia',
        'telefono',
        'email',
        'note',
        'contatti',
        'id_external',
        'dt_import',
        'utente_import',
        'figlio_sn',
        'padre_id',
        'stato',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    /**
     * Genera l'elenco di tutti i clienti
     */

    public function getClienti(): array
    {

        return $this->orderBy('nome', 'ASC')->findAll();
    }

    /**
     * Genera l'elenco dei clienti dato un array di ID
     */
    public function getClientiByIds(int $idClienti): array
    {
        return $this->whereIn('id', $idClienti)->findAll();
    }


    public function getInfoClienti(): array
    {
        return $this->select('id, nome')
            ->orderBy('nome', 'ASC')
            ->findAll();
    }

    /**
     * Recupera un cliente dato il suo ID
     */
    public function getClientiById(int $id): array
    {

        return $this->orderBy('nome', 'ASC')
            ->where('id', $id)
            ->first();
    }
    public function getClientiPadre(): array
    {
        return $this->select([
            'clienti.id as value',
            'CONCAT_WS(\' - \', clienti.nome, clienti.codice) AS content'
        ])
            ->where('figlio_sn', 0)
            ->findAll();
    }

    public function generateInternalCode(): string
    {
        $lastClient = $this->orderBy('id', 'DESC')->first();
        $lastId = $lastClient ? (int)$lastClient['id'] : 0;
        return 'CL' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
    }


    public function getClientiByTipoLicenza(string $tipo): array
    {
        return $this->select('clienti.*')
            ->distinct()
            ->join('licenze', 'licenze.clienti_id = clienti.id')
            ->join('tipilicenze', 'tipilicenze.id = licenze.tipilicenze_id')
            ->where('tipilicenze.tipo', $tipo)
            ->findAll();
    }
}
