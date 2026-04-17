<?php
$siteName = setting('SiteConfig.siteName') ?? 'MeTe Licenze';

// Rileva la URI corrente per evidenziare la voce attiva
$currentUri = '/' . ltrim(service('uri')->getPath(), '/');
?>

<!--
  app-sidebar       → classe AdminLTE per la sidebar laterale
  bg-body-secondary → sfondo leggermente diverso dal body
  data-bs-theme="dark" → forza il tema scuro solo sulla sidebar
-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

  <!-- Brand nella sidebar (visibile su mobile o quando la navbar brand è nascosta) -->
  <div class="sidebar-brand">
    <a href="<?= base_url() ?>" class="brand-link d-flex align-items-center">
      <img src="<?= esc(setting('SiteConfig.logoPath')) ?>" alt="logo"
           class="brand-image opacity-75 shadow" style="max-height: 33px; width: auto;">
      <span class="brand-text fw-light ms-2"><?= esc($siteName) ?></span>
    </a>
  </div>

  <!-- Wrapper scrollabile della sidebar -->
  <div class="sidebar-wrapper">
    <nav class="mt-2" aria-label="Menu principale">
      <!--
        nav sidebar-menu flex-column → lista verticale AdminLTE
        data-lte-toggle="treeview"   → abilita i sottomenu espandibili
        data-accordion="false"       → permette più sottomenu aperti contemporaneamente
      -->
      <ul class="nav sidebar-menu flex-column"
          data-lte-toggle="treeview"
          role="navigation"
          data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?= base_url('/') ?>" class="nav-link <?= ($currentUri === '/') ? 'active' : '' ?>">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Clienti -->
        <li class="nav-item">
          <a href="<?= base_url('clienti') ?>" class="nav-link <?= str_starts_with($currentUri, '/clienti') ? 'active' : '' ?>">
            <i class="nav-icon bi bi-people-fill"></i>
            <p>Clienti</p>
          </a>
        </li>

        <!-- Fornitori (con sottomenu) -->
        <li class="nav-item <?= str_starts_with($currentUri, '/fornitori') || str_starts_with($currentUri, '/tipilicenze') ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= str_starts_with($currentUri, '/fornitori') || str_starts_with($currentUri, '/tipilicenze') ? 'active' : '' ?>">
            <i class="nav-icon bi bi-building"></i>
            <p>
              Fornitori
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <!--
            nav-treeview → classe AdminLTE per il sottomenu
            Viene mostrato/nascosto da AdminLTE JS tramite data-lte-toggle="treeview"
          -->
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('fornitori/') ?>" class="nav-link <?= str_starts_with($currentUri, '/fornitori') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Elenco</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('tipilicenze/') ?>" class="nav-link <?= str_starts_with($currentUri, '/tipilicenze') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tipi Licenze</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Licenze -->
        <li class="nav-item">
          <a href="<?= base_url('licenze') ?>" class="nav-link <?= str_starts_with($currentUri, '/licenze') ? 'active' : '' ?>">
            <i class="nav-icon bi bi-key-fill"></i>
            <p>Licenze</p>
          </a>
        </li>

        <!-- Versioni -->
        <li class="nav-item">
          <a href="<?= base_url('versioni') ?>" class="nav-link <?= str_starts_with($currentUri, '/versioni') ? 'active' : '' ?>">
            <i class="nav-icon bi bi-tags-fill"></i>
            <p>Versioni</p>
          </a>
        </li>

        <!-- Separatore visivo -->
        <li class="nav-header">AMMINISTRAZIONE</li>

        <!-- Admin (con sottomenu) -->
        <li class="nav-item <?= str_starts_with($currentUri, '/admin') ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= str_starts_with($currentUri, '/admin') ? 'active' : '' ?>">
            <i class="nav-icon bi bi-shield-lock-fill"></i>
            <p>
              Admin
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/databaseinfo') ?>" class="nav-link <?= str_starts_with($currentUri, '/admin/databaseinfo') && !str_starts_with($currentUri, '/admin/databaseinfo/showlog') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Test Connessioni</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/users') ?>" class="nav-link <?= str_starts_with($currentUri, '/admin/users') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Utenti</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/settings') ?>" class="nav-link <?= str_starts_with($currentUri, '/admin/settings') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Impostazioni App</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/importClienti') ?>" class="nav-link <?= str_starts_with($currentUri, '/admin/importClienti') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Importa Clienti</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/databaseinfo/showlog') ?>" class="nav-link <?= str_starts_with($currentUri, '/admin/databaseinfo/showlog') ? 'active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Log Database</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
  </div>

</aside>
