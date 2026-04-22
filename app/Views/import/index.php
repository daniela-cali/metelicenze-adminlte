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

    <div class="card-body">

        <div class="mb-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Importazione Clienti
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= url_to('import_clienti_fromDatabase') ?>">Collegamento a database external</a></li>
                    <li><a class="dropdown-item" href="<?= url_to('import_clienti_fromCSV') ?>">Importazione da file CSV</a></li>
                    <li><a class="dropdown-item" href="<?= url_to('import_clienti_create') ?>">Creazione manuale</a></li>
                </ul>
            </div>

        </div>
        <div class="mb-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Dropdown button
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>

        </div>

        <div class="mb-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Dropdown button
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>

        </div>




    </div>
</div>
<?= $this->endSection() ?>