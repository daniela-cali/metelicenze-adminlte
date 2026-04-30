<?php

namespace App\Models;



class LicenzeModel extends AuditModel
{
    protected $table            = 'licenze';
    protected $primaryKey       = 'id';
    protected $beforeInsert =   ['setFakePadre'];
    protected $afterInsert =    ['setPadreSelfIfMissing'];

    protected $allowedFields = [
        'clienti_id',
        'codice',
        'figlio_sn',
        'padre_lic_id',
        'postazioni',
        'note',
        'tipilicenze_id',
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

    public function getLicenze(): array
    {
        $result = $this->select('licenze.*,tipilicenze.tipo as tipilicenze_tipo, tipilicenze.modello as tipilicenze_modello, tipilicenze.categoria as tipilicenze_categoria')
            ->join('tipilicenze', 'licenze.tipilicenze_id = tipilicenze.id')
            ->orderBy('licenze.codice', 'ASC')
            ->get()
            ->getResultArray();
            foreach ($result as &$row) {
                $row['tipilicenze_categoria_label'] = TipiLicenzeModel::decodeCategoriaLabel($row['tipilicenze_categoria']);
                }
                //dd($result);
            return $result;
    }

    public function getLicenzeByCliente(int $idCliente): array
    {
        //log_message('info', 'Recupero le licenze per il cliente con ID: ' . $idCliente . 'e ottengo: ' . print_r($this->where('clienti_id', $idCliente)->findAll(), true));
        $result = $this->db->table('clienti c')
            ->join('licenze l', 'l.clienti_id = c.id')
            ->join('tipilicenze t', 'l.tipilicenze_id = t.id')
            ->where('c.id', $idCliente)
            ->get()
            ->getResultArray();
            //dd($result);
            return $result;
    }

    public function getLicenzeById(int $idLicenza): array
    {   
        $result = $this->select('licenze.*, tipilicenze.tipo as tipilicenze_tipo, tipilicenze.modello as tipilicenze_modello' )
            ->join('tipilicenze', 'tipilicenze.id = licenze.tipilicenze_id')
            ->where('licenze.id', $idLicenza)
            ->get()
            ->getRowArray();
            //dd($result);
        return $result;
    }

    public function getLicenzeByTipo(string $tipoLicenza): array
    {
        $idClientiPerLicenza = $this->select('clienti_id')
            ->distinct()
            ->join('tipilicenze', 'tipilicenze.id = licenze.tipilicenze_id')
            ->where('tipilicenze.tipo', $tipoLicenza)
            ->findAll();
        log_message('info', 'Clienti con licenza di tipo ' . $tipoLicenza . ': ' . print_r($idClientiPerLicenza, true));
        return $idClientiPerLicenza;
    }

    public function geTipoLicenzeByCliente(): array
    {
        $tipoLicenzaPerCliente = $this->select('clienti_id, tipilicenze_id')
            ->join('tipilicenze', 'licenze.tipilicenze_id = tipilicenze.id')
            ->distinct()
            ->findAll();
        //log_message('info', 'tipoLicenzaPerCliente: ' . print_r($tipoLicenzaPerCliente, true));
        return $tipoLicenzaPerCliente;
    }

    protected function setFakePadre(array $data) :array
    {
        // Imposto il valore a numerico positivo fittizio per evitare errori di incorrect integer value, poi dopo l'inserimento lo correggo con setPadreSelfIfMissing
        $insertData = $data['data'] ?? [];
        $insertData['padre_lic_id'] = 0; // Valore fittizio temporaneo
        $data['data'] = $insertData;

        return $data;
    }
    protected function setPadreSelfIfMissing(array $data) :array
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
    public function countLicenzeByCliente(): array
    {
        $rows = $this
            ->select('clienti_id, COUNT(id) AS numLicenze')
            ->groupBy('clienti_id')
            ->findAll();
        $result = array_column($rows, 'numLicenze', 'clienti_id');

        return $result;
    }
    public function getTipoLicenzeByCliente(): array
    {

        /*$rows = $this->select('licenze.clienti_id, t.tipo as tipo_licenza')
            ->join('tipilicenze t', 'licenze.tipilicenze_id = t.id', 'left')
            ->groupBy('licenze.clienti_id, t.tipo')
            ->get()
            ->getResultArray(); // array normale, nessuna indicizzazione su PK come in findAll()*/

            $rows = $this->db->table('licenze l')
                ->select('l.clienti_id, t.tipo as tipo_licenza')
                ->join('tipilicenze t', 'l.tipilicenze_id = t.id', 'left')
                ->groupBy('l.clienti_id, t.tipo')
                ->get()
                ->getResultArray(); 
        // Estraggo un array associativo con clienti_id come chiave e tipo come valore 
        $result = [];
        foreach ($rows as $row) {
            $result[$row['clienti_id']][] = $row['tipo_licenza'];
        }
        //log_message('info', 'tipoLicenzaPerCliente: ' . print_r($result, true));
        return $result;
    }

    public function getDistribuzionePerTipo(): array
    {
        return $this->select('tipo as nome, COUNT(ID) AS totale')
        ->groupBy('tipo')
        ->orderby('totale', 'DESC')
        ->get()
        ->getResultArray();
    }
}
