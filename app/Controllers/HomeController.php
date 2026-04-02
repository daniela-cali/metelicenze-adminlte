<?php

namespace App\Controllers;

use App\Models\ClientiModel;
use App\Models\LicenzeModel;
use App\Models\VersioniModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $config   = config('SiteConfig');
        $loggedIn = auth()->loggedIn();

        // Le query vengono eseguite solo se l'utente è autenticato.
        // Quando non loggato, la home mostra la struttura dell'UI ma senza dati reali:
        // il contenuto del DOM non conterrà numeri sensibili leggibili dai dev tools.
        if ($loggedIn) {
            $clientiModel = new ClientiModel();
            $licenzeModel = new LicenzeModel();

            $totClienti  = $clientiModel->countAll();
            $totLicenze  = $licenzeModel->countAll();
            $totVersioni = (new VersioniModel())->countAll();

            $db = \Config\Database::connect();
            $distribuzione = $db->table('licenze')
                ->select('tipo AS nome, COUNT(id) AS totale')
                ->where('deleted_at IS NULL')
                ->groupBy('tipo')
                ->orderBy('totale', 'DESC')
                ->get()
                ->getResultArray();
        } else {
            // Dati vuoti: nessuna query al DB, nessun dato nel DOM
            $totClienti    = '—';
            $totLicenze    = '—';
            $totVersioni   = '—';
            $distribuzione = [];
        }

        return view('home', [
            'title'         => 'Dashboard',
            'siteName'      => $config->siteName,
            'siteTheme'     => $config->siteTheme,
            'totClienti'    => $totClienti,
            'totLicenze'    => $totLicenze,
            'totVersioni'   => $totVersioni,
            'distribuzione' => $distribuzione,
            'loggedIn'      => $loggedIn,
        ]);
    }
}
