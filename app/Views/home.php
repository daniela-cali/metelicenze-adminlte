<?php $this->extend('layouts/main'); ?>
<?php $this->section('content'); ?>

<header class="bg-light py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Benvenuto in <?= esc($siteName) ?></h1>
        <p class="lead text-muted">Gestisci in modo semplice licenze, versioni e aggiornamenti software</p>
    </div>
</header>

<main class="container my-5">
    <div class="row g-4">

        <!-- Clienti -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-people-fill display-4 text-success"></i>
                    <h5 class="card-title mt-3">Clienti</h5>
                    <p class="card-text text-muted">Visualizza e gestisci l’elenco dei clienti.</p>
                    <a href="<?= base_url('clienti') ?>" class="btn btn-success btn-outline-secondary">Vai ai clienti</a>
                </div>
            </div>
        </div>

        <!-- Licenze -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-key-fill display-4 text-primary"></i>
                    <h5 class="card-title mt-3">Licenze</h5>
                    <p class="card-text text-muted">Visualizza, crea e gestisci le licenze dei clienti.</p>
                    <a href="<?= base_url('licenze') ?>" class="btn btn-primary btn-outline-secondary">Vai alle licenze</a>
                </div>
            </div>
        </div>

        <!-- Versioni -->
        <div class="col">
            <div class="card h-100 text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-tags-fill display-4 text-secondary"></i>
                    <h5 class="card-title mt-3">Versioni</h5>
                    <p class="card-text text-muted">Consulta le versioni disponibili del software.</p>
                    <a href="<?= base_url('versioni') ?>" class="btn btn-secondary btn-outline-secondary">Vai alle versioni</a>
                </div>
            </div>
        </div>

    </div>


</main>

<?php $this->endSection(); ?>