<?php

namespace App\Controllers;

use App\Models\ClientiModel;
use App\Models\LicenzeModel;
use App\Models\TipiLicenzeModel;
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
        $categoriaColori = [
            'gest_contab'    => '#008FFB',
            'fatt_elett'     => '#FEB019',
            'firma_digitale' => '#00E396',
        ];
        $tipiList = (new TipiLicenzeModel())->select('tipo, categoria')->distinct()->findAll();
        $tipoColori = [];
        foreach ($tipiList as $t) {
            $tipoColori[$t['tipo']] = $categoriaColori[$t['categoria']] ?? '#6c757d';
        }
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
            'categoriaColori' => $categoriaColori,
        ];
        return $this->view('home', $data);
    }
}
