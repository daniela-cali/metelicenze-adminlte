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
    protected $afterFind = ['decode_user', 'decode_categoria'];

    public function getTipiLicenza()
    {
        $data = $this->findAll();
        //dd($data);
        foreach ($data as &$item) {
            switch ($item['categoria']) {
                case 'gest_contab':
                    $item['categoria_nome'] = 'Gestionale Contabile';
                    break;
                case 'fatt_elett':
                    $item['categoria_nome'] = 'Fatturazione Elettronica';
                    break;
                case 'firma_digitale':
                    $item['categoria_nome'] = 'Servizio di Firma Digitale';
                    break;
                default:
                    $item['categoria_nome'] = 'Non specificata';
            }
        }
        return $data;
    }
    public function getTipiLicenzaById($id)
    {
        return $this->where('id', $id)->first();
    }
    public function getTipiLicenzeByFornitore($idFornitore)
    {
        return $this->select('tipilicenze.*')
            ->join('fornitori_tipilicenze_map', 'fornitori_tipilicenze_map.tipilicenze_id = tipilicenze.id')
            ->where('fornitori_tipilicenze_map.fornitori_id', $idFornitore)
            ->findAll(); //Uso findAll() per fare in modo che si attivi la callback decode_categoria
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
        if (isset($data['data'])) {
            foreach ($data['data'] as &$row) {
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
        }

        return $data;
    }
}
