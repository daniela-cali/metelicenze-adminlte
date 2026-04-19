<?php

namespace App\Controllers;

use App\Models\ClientiModel;
use App\Models\LicenzeModel;
use App\Models\VersioniModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $loggedIn = auth()->loggedIn();

        // Le query vengono eseguite solo se l'utente è autenticato.
        // Quando non loggato, la home mostra la struttura dell'UI ma senza dati reali:
        // il contenuto del DOM non conterrà numeri sensibili leggibili dai dev tools.
        if ($loggedIn) {

            $totClienti  = (new ClientiModel())->countAll();
            $totLicenze  = (new LicenzeModel())->countAll();
            $totVersioni = (new VersioniModel())->countAll();

            $distribuzione = (new LicenzeModel())->getDistribuzionePerTipo();
            $versioni = (new VersioniModel())->getUltimeVersioni();
        } else {
            // Dati vuoti: nessuna query al DB, nessun dato nel DOM
            $totClienti    = '—';
            $totLicenze    = '—';
            $totVersioni   = '—';
            $distribuzione = [];
            $versioni = [];
        }
        $tipoColori = [
            'Sigla'  => '#008FFB',
            'VarHub' => '#FEB019',
            'SKNT'   => '#00E396',
        ];
        $data = [
            'title'         => 'Dashboard',
            'siteName'      => setting('SiteConfig.siteName'),
            'siteTheme'     => setting('SiteConfig.siteTheme'),
            'totClienti'    => $totClienti,
            'totLicenze'    => $totLicenze,
            'totVersioni'   => $totVersioni,
            'distribuzione' => $distribuzione,
            'versioni'      => $versioni,
            'loggedIn'      => $loggedIn,
            'tipoColori'    => $tipoColori,
        ];
        return $this->view('home', $data);
    }
}
