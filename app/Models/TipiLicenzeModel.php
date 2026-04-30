<?php

namespace App\Models;



class TipiLicenzeModel extends AuditModel
{
    protected $table            = 'tipilicenze';
    protected $primaryKey       = 'id';
    protected $allowedFields = [
        'tipo',
        'descrizione',
        'modello',
        'fornitori_id',
        'categoria',
        'stato',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'created_at',
        'updated_at',
    ];
    protected $afterFind = ['decode_categoria'];

    public function getTipiLicenza()
    {
        $data = $this->findAll();
        //dd($data);        
        return $data;
    }

    public function getTipiLicenzaById(int $id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Ottiene l'elenco di tutte le licenze fornite da un fornitore
     */
    public function getTipiLicenzeByFornitore(int $idFornitore)
    {
        $recordset = $this->select('tipilicenze.*')
            ->where('fornitori_id', $idFornitore)
            ->findAll(); //Uso findAll() per fare in modo che si attivi la callback decode_categoria
        log_message('info', 'TipiLicenzeModel::getTipiLicenzeByFornitore - Recordset per fornitore ' . $idFornitore . ': ' . print_r($recordset, true));

        return $recordset;
    }

    /**
     * Genera la select da mandare alla view
     */
    public function getTipiLicenzaForSelect()
    {
        $data = $this->findAll();
        $selectData = [];
        foreach ($data as $item) {
            $selectData[] = [
                'id' => $item['id'],
                'value' => $item['nome'] . ' - ' . $item['modello'],
            ];
        }
        return $selectData;
    }

    /**
     * Crea associazione tra tipo licenza e fornitore
     */
    public function linkFornitore(int $idFornitore, int $idTipoLicenze)
    {
        log_message('info', 'Tentativo di associare Fornitore ID: ' . $idFornitore . ' al TipoLicenze ID: ' . $idTipoLicenze);
        /*$data = [
            'fornitori_id' => $idFornitore,
        ];*/
        try {
            $res = $this->set('fornitori_id', $idFornitore)
                ->where('id', $idTipoLicenze)
                ->update();
            return $res !== false; // Verifica se l'inserimento è avvenuto con successo restituendo true SOLO se diverso da false (inserimento riuscito)

        } catch (\Exception $e) {
            log_message('error', 'Errore durante l\'associazione del fornitore al tipo di licenza: ' . $e->getMessage());
            return false;
        }
    }
    /**
     * Toglie associazione tra tipo licenza e fornitore
     */
    public function unlinkFornitore(int $idTipoLicenze)
    {
        try {
            $res = $this->set('fornitori_id', null)
                ->where('id', $idTipoLicenze)
                ->update();
            return $res !== false; // Verifica se l'inserimento è avvenuto con successo restituendo true SOLO se diverso da false (inserimento riuscito)

        } catch (\Exception $e) {
            log_message('error', 'Errore durante la disassociazione del fornitore al tipo di licenza: ' . $e->getMessage());
            return false;
        }
    }

    public static function decodeCategoriaLabel(?string $categoria) :string {
        return match($categoria) {
            'gest_contab' => 'Gestionale Contabile',
            'fatt_elett' => 'Fatturazione elettronica',
            'firma_digitale' => 'Servizio di Firma Digitale',
            default => 'Non specificato'
        };
    }
    /**
     * Decodifica la categoria (enum a db)
     */
    protected function decode_categoria(array $data)
    {
        // Se non c'è data, esco
        if (! isset($data['data']) || empty($data['data'])) {
            return $data;
        }

        // CI4: quando il risultato è singolo, 'singleton' è true e data è una riga (assoc)
        $isSingleton = !empty($data['singleton']);


        // Normalizzo sempre in "lista di righe"
        $rows = $isSingleton ? [$data['data']] : $data['data'];
        //if ($isSingleton) log_message('info', 'TipiLicenzeModel::decode_categoria - Righe da decodificare: ' . print_r($rows, true) . ' - Singleton: ' . ($isSingleton ? 'Sì' : 'No'));

        foreach ($rows as &$row) {
            if ($isSingleton) log_message('info', 'TipiLicenzeModel::decode_categoria - Decodifico categoria per riga: ' . print_r($row, true));
            $row['categoria_label'] = self::decodeCategoriaLabel($row['categoria']);
        }

        // Se per qualche motivo non è un array di righe, esco
        if (! is_array($rows)) {
            return $data;
        }

        // Aggiorno $data['data'] con le righe modificate, gestendo il caso singleton
        if (isset($data['data'])) {
            $data['data'] = $isSingleton ? $rows[0] : $rows;
        }
        return $data;
    }
}
