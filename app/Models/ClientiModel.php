<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientiModel extends Model
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
        'stato',
        'created_at',
        'updated_at',
        'utente_import',
    ];
    protected $returnType       = 'object';



    /**
     * Genera l'elenco di tutti i clienti
     */

    public function getClienti()
    {
        return $this->orderBy('nome', 'ASC')->findAll();
    }

    /**
     * Genera l'elenco dei clienti dato un array di ID
     */
    public function getClientiByIds($idClienti)
    {
        return $this->whereIn('id', $idClienti)->findAll();
    }


    public function getInfoClienti()
    {
        return $this->select('id, nome')
            ->orderBy('nome', 'ASC')
            ->findAll();
    }

    /**
     * Recupera un cliente dato il suo ID
     */
    public function getClientiById($id)
    {

        return $this->orderBy('nome', 'ASC')
            ->where('id', $id)
            ->first();
    }

    public function salva($data)
    {

        //log_message('info', 'Ricevo i seguenti dati nel MODEL: ' . print_r($data, true));
        $this->save($data); 
        log_message('info', 'Dopo il salvataggio, l\'ID del cliente è: ' . $this->insertID);
        return $this->insertID;// Restituisce l'ID del nuovo cliente
    }

}
