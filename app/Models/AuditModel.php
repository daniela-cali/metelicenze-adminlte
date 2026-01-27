<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditModel extends Model
{

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['created_by'];
    protected $beforeUpdate = ['updated_by'];
    protected $afterFind = ['decode_user'];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];

    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function created_by(array $data)
    {
        $user = auth()->user();

        if ($user) {
            $data['data']['created_by'] = $user->id ?? null;
            $data['data']['updated_by'] = $user->id ?? null;
        }
        return $data;
    }

    protected function updated_by(array $data)
    {
        $user = auth()->user();

        if ($user) {
            $data['data']['updated_by'] = $user->id ?? null;
        }
        return $data;
    }
    protected function decode_user(array $data)
    {
        // Se non c'è data, esco
        if (! isset($data['data']) || empty($data['data'])) {
            return $data;
        }

        // CI4: quando il risultato è singolo, 'singleton' è true e data è una riga (assoc)
        $isSingleton = !empty($data['singleton']);

        // Normalizzo sempre in "lista di righe"
        $rows = $isSingleton ? [$data['data']] : $data['data'];

        // Se per qualche motivo non è un array di righe, esco
        if (! is_array($rows)) {

            //dd($rows);
            return $data;
        }

        // (1) - Preparo un array di ID di utenti da caricare dal database        

        if (!empty($rows)) {
            $user_ids  = array();
            foreach ($rows as $row) {
                $created_by = array_key_exists('created_by', $row) ? $row['created_by'] : null;
                $updated_by = array_key_exists('updated_by', $row) ? $row['updated_by'] : null;
                if (!empty($created_by) && !in_array($created_by, $user_ids)) {
                    $user_ids[] = $created_by;
                }
                if (!empty($updated_by) && !in_array($updated_by, $user_ids)) {
                    $user_ids[] = $updated_by;
                }
            }
            // (2) - Esecuzione della query per recuperare tutti gli ID degli utenti in una volta sola        
            if (!empty($user_ids)) {
                $userModel = new \App\Models\UserModel();
                $users = array_column($userModel->whereIn('id', $user_ids)->findAll(), null, 'id');
            } else {
                $users = array();
            }
            // (3) - Assegnazione dei nomi degli utenti che hanno creato o modificato i record        
            foreach ($rows as &$row) {
                $created_by = array_key_exists('created_by', $row) ? $row['created_by'] : null;
                $updated_by = array_key_exists('updated_by', $row) ? $row['updated_by'] : null;
                if (!empty($created_by)) {
                    if (array_key_exists($created_by, $users)) {
                        $row['created_by_name'] = $users[$created_by]["username"];
                    } else {
                        $row['created_by_name'] = 'Utente non trovato';
                    }
                } else {
                    $row['created_by_name'] = 'N/A';
                }
                if (!empty($updated_by)) {
                    if (array_key_exists($updated_by, $users)) {
                        $row['updated_by_name'] = $users[$updated_by]["username"];
                    } else {
                        $row['updated_by_name'] = 'Utente non trovato';
                    }
                } else {
                    $row['updated_by_name'] = 'N/A';
                }
            }
            // Ripristino il formato originale di ritorno
            if ($isSingleton) {
                $data['data'] = $rows[0];
            } else {
                $data['data'] = $rows;
            }
        }

        return $data;
    }

    public function getClienti()
    {
        $test = 'decode_user';
        return $this->{$test}([]);
    }
}
