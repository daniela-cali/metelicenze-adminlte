<?php

namespace App\Controllers;

use App\Models\ClientiModel;
use App\Models\LicenzeModel;
use App\Models\VersioniModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $config = config('SiteConfig');

        $clientiModel = new ClientiModel();
        $licenzeModel = new LicenzeModel();

        // Conteggi generali
        $totClienti  = $clientiModel->countAll();
        $totLicenze  = $licenzeModel->countAll();
        $totVersioni = (new VersioniModel())->countAll();

        // Distribuzione licenze per tipo (campo tipo su licenze)
        $db = \Config\Database::connect();
        $distribuzione = $db->table('licenze')
            ->select('tipo AS nome, COUNT(id) AS totale')
            ->where('deleted_at IS NULL')
            ->groupBy('tipo')
            ->orderBy('totale', 'DESC')
            ->get()
            ->getResultArray();

        return view('home', [
            'title'         => 'Dashboard',
            'siteName'      => $config->siteName,
            'siteTheme'     => $config->siteTheme,
            'totClienti'    => $totClienti,
            'totLicenze'    => $totLicenze,
            'totVersioni'   => $totVersioni,
            'distribuzione' => $distribuzione,
        ]);
    }
}
