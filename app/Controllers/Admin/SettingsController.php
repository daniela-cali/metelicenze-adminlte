<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Settings\SiteSettings;

class SettingsController extends BaseController
{
    protected $settingsService;

    public function __construct()
    {
        $this->settingsService = service('settings');
    }

    public function index()
    {
        // Legge l'intero oggetto SiteSettings dal DB
        /*$siteSettings = $this->settingsService->get(SiteSettings::class);

        // Se non esiste ancora, crea un oggetto vuoto
        if (!$siteSettings) {
            $siteSettings = new SiteSettings();
        }*/
        //$siteSettings = $this->settingsService->get(SiteSettings::class) ?? new SiteSettings();
        return view('admin/settings_form');
    }

    public function save()
    {
        $post = $this->request->getPost();

        $siteSettings = new SiteSettings();
        $siteSettings->siteName = $post['siteName'] ?? '';
        $siteSettings->siteTheme = $post['siteTheme'] ?? '';
        $siteSettings->adminEmail = $post['adminEmail'] ?? '';
        $siteSettings->siteURL = $post['siteURL'] ?? '';
        $siteSettings->maintenanceMode = !empty($post['maintenanceMode']);

        // Salva nel DB
        $this->settingsService->save($siteSettings);

        return redirect()->to('/admin/settings')->with('success', 'Impostazioni salvate correttamente!');
    }
}
