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
                <?php foreach ($tables as $key => $table): ?>
                    <?php $hasFields = $nullCounts[$table['tabella_dest']]== 0 ? true : false; ?>


                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header d-flex">
                                <h6 class="mb-0 text-capitalize"><?= esc($table['tabella_dest']) ?></h6>
                                <a href="#"
                                    class="btn btn-outline-secondary btn-sm ms-auto"
                                    data-bs-toggle="modal"
                                        data-bs-target="#uploadFile"
                                        data-tabella="<?= esc($table['tabella_dest']) ?>"
                                        data-tipo="importa_colonne">
                                    <i class="bi bi-link"></i>
                                    Avvia mappatura campi
                                </a>

                            </div>
                            <div class="card-body">
                                <span <?=$hasFields ? '' : 'data-bs-toggle="tooltip" title="Completa prima la mappatura dei campi"' ?>>
                                    <button type="button"
                                        <?= $hasFields ? '' : 'disabled' ?>
                                        title="Importa dati da file CSV"
                                        class="btn btn-outline-info btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#uploadFile"
                                        data-tabella="<?= esc($table['tabella_dest']) ?>"
                                        data-tipo="importa_file">
                                        <i class="bi bi-filetype-csv"></i>
                                        Da CSV
                                    </button>
                                </span>
                                <span <?= $hasFields ? '' : 'data-bs-toggle="tooltip" title="Completa prima la mappatura dei campi"' ?>>
                                    <a 
                                        href="<?= url_to('import_from_database', $table['tabella_dest']) ?>"
                                        <?= $hasFields ? '' : 'disabled' ?>
                                        title="Importa dati da database esterno"
                                        class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-database"></i>
                                        Da Database
                                    </a>
                                </span>


                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<!-- Modal upload CSV -->
<div class="modal fade" id="uploadFile" tabindex="-1" aria-labelledby="uploadFile" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFile">Upload File CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Caricare il file .csv per importare le colonne del database di origine
                <form action="<?= url_to('import_uploadCsv'); ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="text" name="tabella" id="tabellaInput" value="">
                    <input type="text" name="tipo" id="tipo" value="">
                    <div class="my-3">
                        <input required class="form-control" type="file" id="uploadedFile" name="uploadedFile" accept=".csv">
                        <div id="columnNameDIV">
                            <label for="columnName">Nome Colonna CSV</label>
                            <input type="text" name="columnName" class="form-control" value="column_name">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <button type="submit" class="btn btn-primary">Procedi</button>
            </div>
            </form>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('uploadFile').addEventListener('show.bs.modal', function(e) {
            console.log(e.relatedTarget.dataset)
            document.getElementById('tabellaInput').value = e.relatedTarget.dataset.tabella;
            document.getElementById('tipo').value = e.relatedTarget.dataset.tipo;
            const tipo = e.relatedTarget.dataset.tipo;
            const colonnaDiv = document.getElementById('columnNameDIV');
            colonnaDiv.style.display = (tipo === 'importa_colonne') ? 'block' : 'none';
        });
    });
</script>
<?= $this->endSection() ?>