<?php

namespace App\Models;



class LicenzeModel extends AuditModel
{
    protected $table            = 'licenze';
    protected $primaryKey       = 'id';
    protected $beforeInsert =   ['created_by', 'setFakePadre'];
    protected $afterInsert =    ['updated_by','setPadreSelfIfMissing'];

    protected $allowedFields = [
        'clienti_id',
        'codice',
        'figlio_sn',
        'padre_lic_id',
        'postazioni',
        'note',
        'tplicenze_id',
        'stato',
        'esistenza_cliente',
        'natura',
        'tipo',
        'modello',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'server',
        'conn',
        'ambiente',
        'nodo',
        'invii',
        'giga',
    ];


    /**
     * Genera l'elenco delle licenze
     */

    public function getLicenze()
    {

        return $this->select('*')
            ->orderBy('codice', 'ASC')
            ->findAll();
    }

    public function getLicenzeByCliente($idCliente)
    {
        //log_message('info', 'Recupero le licenze per il cliente con ID: ' . $idCliente . 'e ottengo: ' . print_r($this->where('clienti_id', $idCliente)->findAll(), true));
        return $this->where('clienti_id', $idCliente)->findAll();
    }

    public function getLicenzeById($idLicenza)
    {   //Sto cercando per chiave primaria pertanto non serve il where
        return $this->find($idLicenza);
    }

    public function getLicenzeByTipo($tipoLicenza)
    {

        $idClientiPerLicenza = $this->select('clienti_id')
            ->distinct()
            ->where('tipo', $tipoLicenza)
            ->findAll();
        log_message('info', 'Clienti con licenza di tipo ' . $tipoLicenza . ': ' . print_r($idClientiPerLicenza, true));
        return $idClientiPerLicenza;
    }

    public function geTipoLicenzeByCliente()
    {
        $tipoLicenzaPerCliente = $this->select('clienti_id, tipo')
            ->distinct()
            ->findAll();
        //log_message('info', 'tipoLicenzaPerCliente: ' . print_r($tipoLicenzaPerCliente, true));
        return $tipoLicenzaPerCliente;
    }

    protected function setFakePadre(array $data)
    {
        // Imposto il valore a numerico positivo fittizio per evitare errori di incorrect integer value, poi dopo l'inserimento lo correggo con setPadreSelfIfMissing
        $insertData = $data['data'] ?? [];
        $insertData['padre_lic_id'] = 0; // Valore fittizio temporaneo
        $data['data'] = $insertData;

        return $data;
    }
    protected function setPadreSelfIfMissing(array $data)
    {
        // Dopo l'inserimento, prendo l'ID appena creato
        $newId = $data['id'] ?? null;
        if (!$newId) {
            return $data; //Altrimenti non faccio nulla se non c'è un nuovo ID
        }

        // Se nel POST/insert è già arrivato padre_lic_id (figlio), NON tocco niente.
        // Se manca / è vuoto / è 0, lo imposto a se stessa.
        $insertData = $data['data'] ?? [];
        $padre = $insertData['padre_lic_id'] ?? null;

        // Normalizzo il valore di padre_lic_id
        $padre = (is_numeric($padre) && (int)$padre > 0) ? (int)$padre : null;

        if ($padre === null) {
            // update diretto per evitare loop/callback ricorsivi
            $this->builder()
                ->where($this->primaryKey, $newId)
                ->update(['padre_lic_id' => $newId]);
        }

        return $data;
    }
    public function countLicenzeByCliente()
    {
        $rows = $this
            ->select('clienti_id, COUNT(id) AS numLicenze')
            ->groupBy('clienti_id')
            ->findAll();
        $result = array_column($rows, 'numLicenze', 'clienti_id');

        return $result;
    }
    public function getTipoLicenzeByCliente()
    {
        $rows = $this->select('clienti_id, tipo')
            ->groupBy('clienti_id, tipo')
            ->get()
            ->getResultArray(); // array normale, nessuna indicizzazione su PK come in findAll()
        // Estraggo un array associativo con clienti_id come chiave e tipo come valore 
        $result = [];
        foreach ($rows as $row) {
            $result[$row['clienti_id']][] = $row['tipo'];
        }
        //log_message('info', 'tipoLicenzaPerCliente: ' . print_r($result, true));
        return $result;
    }
}
