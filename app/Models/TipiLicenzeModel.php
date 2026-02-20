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
        $this->select('tipilicenze.*');
        $this->join('fornitori_tipilicenze_map', 'fornitori_tipilicenze_map.tipilicenze_id = tipilicenze.id');
        $this->where('fornitori_tipilicenze_map.fornitori_id', $idFornitore);
        return $this->findAll();
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
    
}
