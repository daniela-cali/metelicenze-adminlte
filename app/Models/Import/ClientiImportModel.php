<?php

namespace App\Models\Import;

use CodeIgniter\Model;

class ClientiImportModel extends Model
{

    protected $table            = 'clienti';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $uniqueKeys = ['id_external'];
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



}
