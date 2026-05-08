<?php

namespace App\Models\Import;

use CodeIgniter\Model;

class FornitoriImportModel extends Model
{

    protected $table            = 'fornitori';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $uniqueKeys       = ['id_external'];
    protected $allowedFields = [
        'codice', 
        'id_external', 
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
        'dt_import', 
        'utente_import', 
    ];



}
