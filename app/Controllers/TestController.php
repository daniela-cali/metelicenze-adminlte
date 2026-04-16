<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends BaseController
{
    public function aggiornamentiModel($metodo)
    {
        $model = new \App\Models\AggiornamentiModel();
        // Esegui il metodo specificato
        if (method_exists($model, $metodo)) {
            $test = $model->$metodo();
            return $this->view('test/test', ['test' => $test]);
        } else {
            return redirect()->back()->with('error', 'Metodo non trovato.');
        }
    }
    public function clienti($metodo)
    {
        // Esegui il metodo specificato
        if (method_exists($this, $metodo)) {
            return $this->$metodo();
        } else {
            return redirect()->back()->with('error', 'Metodo non trovato.');
        }
    }

}
