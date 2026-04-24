<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Import</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-box-arrow-down"></i> Pagina di importazione dati</h5>

</div>
<div class="card shadow-sm">
    <div class="card-header card-header-muted d-flex align-items-center">
        <h5 class="mb-0"> Scelta del tipo</h5>
        <a href="" class="btn btn-light btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Indietro
        </a>
    </div>
    <?php if (empty($tables)): ?>
        <div class="card-body">

            <div class="mb-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Tabella Transcodifiche vuota!</h3>
                        <p>Eseguire prima l'importazione dei campi del database interno</p>
                    </div>

                </div>
            </div>
            <a href="<?= url_to('import_loadTablesFields') ?>" class="btn btn-secondary btn-sm" title="Avvia importazione">
                <i class="bi bi-database-fill-down"></i> Avvia importazione
            </a>
        </div>
    <?php else: ?>
        <div class="card-body">
            <div class="row g-3">
                <?php foreach ($tables as $table): ?>
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header">
                                <h6 class="mb-0 text-capitalize"><?= esc($table['tabella_dest']) ?></h6>
                            </div>
                            <div class="card-body">
                                <a href="#" class="btn btn-outline-primary btn-sm me-1">
                                    <i class="bi bi-file-earmark-csv"></i> Da CSV
                                </a>
                                <a href="#" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-database"></i> Da Database
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>



    <?= $this->endSection() ?>