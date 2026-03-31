<?php
$config   = config('SiteConfig');
$siteName = $config->siteName ?? 'MeTe Licenze';
?>

<!--
  app-header  → classe AdminLTE per la barra superiore fissa
  navbar-expand → espanso di default (non collassa su mobile, lo fa la sidebar)
  bg-body     → sfondo adattivo Bootstrap 5 (bianco in light, scuro in dark)
-->
<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">

    <!-- Sinistra: toggle sidebar + brand -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <!--
          data-lte-toggle="sidebar" → AdminLTE JS intercetta questo attributo
          e apre/chiude la sidebar al click
        -->
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-flex align-items-center ms-2">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>">
          <img src="<?= esc($config->logoPath) ?>" alt="logo" style="max-height: 32px; width: auto;" class="me-2">
          <span class="fw-semibold"><?= esc($siteName) ?></span>
        </a>
      </li>
    </ul>

    <!-- Destra: utente -->
    <ul class="navbar-nav ms-auto">

      <?php if (function_exists('auth') && auth()->loggedIn()): ?>

        <!-- Menu utente -->
        <li class="nav-item dropdown user-menu">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>
            <span class="d-none d-md-inline"><?= esc(auth()->user()->username) ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="<?= base_url('utenti/visualizza/' . auth()->user()->id) ?>">
                <i class="bi bi-person me-2"></i> Profilo
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </a>
            </li>
          </ul>
        </li>

      <?php else: ?>

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('login') ?>">
            <i class="bi bi-box-arrow-in-left me-1"></i> Login
          </a>
        </li>

      <?php endif; ?>

    </ul>

  </div>
</nav>
