<?php $this->extend('layouts/main'); ?>

<?php $this->section('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">
<?php $this->endSection(); ?>

<?php $this->section('breadcrumb'); ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item active">Dashboard</li>
</ol>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>

<!-- Riga contatori -->
<div class="row g-3 mb-4">

    <!-- Clienti -->
    <div class="col-12 col-sm-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon text-bg-success">
                <i class="bi bi-people-fill"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Clienti</span>
                <span class="info-box-number"><?= $totClienti ?></span>
                <a href="<?= url_to('clienti_index') ?>" class="small text-muted">Vai all'elenco &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Licenze -->
    <div class="col-12 col-sm-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon text-bg-primary">
                <i class="bi bi-key-fill"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Licenze</span>
                <span class="info-box-number"><?= $totLicenze ?></span>
                <a href="<?= url_to('licenze_index') ?>" class="small text-muted">Vai all'elenco &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Versioni -->
    <div class="col-12 col-sm-4">
        <div class="info-box shadow-sm">
            <span class="info-box-icon text-bg-warning">
                <i class="bi bi-tags-fill"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Versioni</span>
                <span class="info-box-number"><?= $totVersioni ?></span>
                <a href="<?= url_to('versioni_index') ?>" class="small text-muted">Vai all'elenco &rarr;</a>
            </div>
        </div>
    </div>

</div>

<!-- Grafico distribuzione licenze per tipo -->
<div class="row">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart-fill me-2"></i> Distribuzione licenze per tipo
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($distribuzione)): ?>
                    <div id="chart-distribuzione"></div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">Nessuna licenza presente</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-tags-fill me-2"></i> Ultime versioni
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($versioni)): ?>
                    <div style="display: grid; grid-template-columns: max-content 1fr; gap: 0.4rem 0.6rem; align-items: center;">
                        <?php foreach ($versioni as $versione): ?>
                            <span class="badge rounded-pill text-center"
                                  style="background-color: <?= $tipoColori[$versione['tipo']] ?? '#6c757d' ?>">
                                <?= esc($versione['tipo']) ?>
                            </span>
                            <div>
                                <div class="fw-bold"><?= esc($versione['codice']) ?></div>
                                <div class="text-muted small">Rilasciata il <?= date('d/m/Y', strtotime($versione['dt_rilascio'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <p class="text-center text-muted py-3">Nessuna versione presente</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>




<?php if (!$loggedIn): ?>
    <!-- Modal di login: si sovrappone al contenuto sfocato, non chiudibile dall'utente -->
    <div class="modal fade" id="loginModal" tabindex="-1"
        data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="loginModalLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">

                <!-- Header scuro con logo e nome dell'applicazione -->
                <div class="modal-header">
                    <img src="<?= setting('SiteConfig.logoPath') ?>"
                        alt="<?= esc(setting('SiteConfig.siteName')) ?>"
                        class="me-3">
                    <h5 class="modal-title mb-0" id="loginModalLabel">
                        <?= esc(setting('SiteConfig.siteName')) ?> — Accesso
                    </h5>
                </div>

                <!-- Form di login riusato dal partial Shield/_login_form -->
                <div class="modal-body px-4 pb-4">
                    <?= view('Shield/_login_form') ?>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
<?php if (!empty($distribuzione)): ?>
    <script>
        const distribuzioneData = <?= json_encode(array_values($distribuzione)) ?>;

        const chart = new ApexCharts(document.querySelector('#chart-distribuzione'), {
            series: distribuzioneData.map(r => parseInt(r.totale)),
            labels: distribuzioneData.map(r => r.nome ?? 'Non specificato'),
            chart: {
                type: 'donut',
                height: 320,
            },
            legend: {
                position: 'bottom',
            },
            dataLabels: {
                formatter: (val, opts) => {
                    return opts.w.config.series[opts.seriesIndex];
                },
            },
            tooltip: {
                y: {
                    formatter: val => val + ' licenze',
                },
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Totale',
                                formatter: () => <?= $totLicenze ?>,
                            },
                        },
                    },
                },
            },
        });

        chart.render();
    </script>
<?php endif; ?>

<?php if (!$loggedIn): ?>
    <script>
        // Modalità ospite: attiva blur sull'app e mostra il modal di login
        document.addEventListener('DOMContentLoaded', function() {
            // Sposta il modal come figlio diretto di <body> (era dentro .app-wrapper)
            // così non viene colpito dal filter:blur applicato all'app-wrapper
            document.body.appendChild(document.getElementById('loginModal'));

            // Aggiunge la classe che applica il filtro blur all'app-wrapper via custom.css
            document.body.classList.add('guest-mode');

            // Mostra il modal in modo non chiudibile (backdrop=static, keyboard=false)
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'), {
                backdrop: 'static',
                keyboard: false,
            });
            loginModal.show();
        });
    </script>
<?php endif; ?>

<?php $this->endSection(); ?>