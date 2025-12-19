<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class SiteConfig extends BaseConfig
{
    public string $siteName = 'MeTe Licenze';
    public string $siteTheme = 'metelicenze'; // Default theme
    public string $adminEmail = 'nhildra.morwen@gmail.com';
    public string $siteURL    = 'https://metelicenze.unresolved.it/'; 

    // Abilita o disabilita la connessione a un secondo database
    public bool $enableMultiDB = false;
}