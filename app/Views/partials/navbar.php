<?php
// Carica le configurazioni del sito
$config = config('SiteConfig');
$siteName = $config->siteName ?? 'MeTe Licenze';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>">
            <img src="<?=  config('SiteConfig')->logoPath;
            //log_message('debug', 'Logo path: ' . config('SiteConfig')->logoPath) ?>" alt="logo" class="logo-navbar me-2">
            <span><?= esc($siteName) ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('clienti') ?>">Clienti</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('licenze') ?>">Licenze</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('versioni') ?>">Versioni</a></li>

                <!-- Dropdown Database -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="databaseDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Admin
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="databaseDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('database/') ?>">Test Connessioni</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('utenti/') ?>">Utenti</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>">Impostazioni App</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('admin/import_clienti') ?>">Importa Clienti</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('database/log') ?>">Log Database</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="navbar-nav">
                <?php if (function_exists('auth') && auth()->loggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('utenti/visualizza/' . auth()->user()->id) ?>">
                            <i class="bi bi-person-circle"></i> <?= esc(auth()->user()->username) ?>
                        </a>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login') ?>">
                            <i class="bi bi-box-arrow-in-left"></i> Login
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('register') ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
