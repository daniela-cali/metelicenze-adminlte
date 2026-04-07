<?php

namespace App\Models;



class TipiLicenzeModel extends AuditModel
{
    protected $table            = 'tipilicenze';
    protected $primaryKey       = 'id';
    protected $allowedFields = [
        'nome',
        'descrizione',
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
    public function getTipiLicenzaById($id)
    {
        return $this->where('id', $id)->first();
    }
    public function getTipiLicenzeByFornitore($idFornitore)
    {
        $recordset = $this->select('tipilicenze.*')
            ->join('fornitori_tipilicenze_map', 'fornitori_tipilicenze_map.tipilicenze_id = tipilicenze.id')
            ->where('fornitori_tipilicenze_map.fornitori_id', $idFornitore)
            ->findAll(); //Uso findAll() per fare in modo che si attivi la callback decode_categoria
            log_message('info', 'TipiLicenzeModel::getTipiLicenzeByFornitore - Recordset per fornitore ' . $idFornitore . ': ' . print_r($recordset, true));

            return $recordset;
    }

    public function getTipiLicenzaForSelect()
    {
        $data = $this->findAll();
        $selectData = [];
        foreach ($data as $item) {
            $selectData[] = [
                'id' => $item['id'],
                'value' => $item['nome'],
            ];
        }
        return $selectData;
    }
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
            switch ($row['categoria']) {
                case 'gest_contab':
                    $row['categoria_label'] = 'Gestionale Contabile';
                    break;
                case 'fatt_elett':
                    $row['categoria_label'] = 'Fatturazione Elettronica';
                    break;
                case 'firma_digitale':
                    $row['categoria_label'] = 'Servizio di Firma Digitale';
                    break;
                default:
                    $row['categoria_label'] = 'Non specificata';
            }
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
