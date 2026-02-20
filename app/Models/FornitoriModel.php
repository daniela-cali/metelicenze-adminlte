<?php

namespace App\Models;

use CodeIgniter\Model;

class FornitoriModel extends AuditModel
{
    protected $table            = 'fornitori';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
       'id_external', 
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
       'stato', 
       'created_by', 
       'updated_by', 
       'deleted_at', 
       'deleted_by', 
       'created_at', 
       'updated_at', 
 ];
    /**
     * Genera l'elenco di tutti i fornitori
     */

    public function getFornitori()
    {
        
        return $this->orderBy('nome', 'ASC')->findAll();
    }

    /**
     * Genera l'elenco dei fornitori dato un array di ID
     */
    public function getFornitoriByIds($idFornitori)
    {
        return $this->whereIn('id', $idFornitori)->findAll();
    }


    public function getInfoFornitori()
    {
        return $this->select('id, nome')
            ->orderBy('nome', 'ASC')
            ->findAll();
    }

    /**
     * Recupera un fornitore dato il suo ID
     */
    public function getFornitoriById($id)
    {

        return $this->orderBy('nome', 'ASC')
            ->where('id', $id)
            ->first();
    }


}
