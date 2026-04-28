<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClientiVincoloUnivoco extends Migration
{
    public function up()
    {
        $this->forge->addKey('id_external', false, true); // unique key

    }

    public function down()
    {
        
    }
}
