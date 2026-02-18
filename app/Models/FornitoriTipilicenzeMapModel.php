<?php

namespace App\Models;

use CodeIgniter\Model;

class FornitoriTipilicenzeMapModel extends Model
{
    protected $table      = 'fornitori_tipilicenze_map';
    protected $returnType = 'array';

    protected $allowedFields = [
        'fornitori_id', 
        'tipilicenze_id',  
        'created_at', 
    ];

        public function getTipiLicenzeByFornitore($idFornitore)
        {
            return $this->where('fornitori_id', $idFornitore)->findAll();
        }

        public function getFornitoreByTipoLicenze($idTipoLicenze)
        {
            return $this->where('tipilicenze_id', $idTipoLicenze)->findAll();
        }

        public function linkFornitoreToTipoLicenze($idFornitore, $idTipoLicenze)
        {
            log_message('info', 'Tentativo di associare Fornitore ID: ' . $idFornitore . ' al TipoLicenze ID: ' . $idTipoLicenze);
            $data = [
                'fornitori_id' => $idFornitore,
                'tipilicenze_id' => $idTipoLicenze,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            try {
                $res = $this->insert($data);
                return $res !== false; // Verifica se l'inserimento è avvenuto con successo restituendo true SOLO se diverso da false (inserimento riuscito)

            } catch (\Exception $e) {
                log_message('error', 'Errore durante l\'associazione del fornitore al tipo di licenza: ' . $e->getMessage());
                return false;
            }
        }
        public function unlinkFornitoreFromTipoLicenze($idFornitore, $idTipoLicenze)
        {
            return $this->where('fornitori_id', $idFornitore)
                        ->where('tipilicenze_id', $idTipoLicenze)
                        ->delete();
        }
}
