<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary  d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-eye"></i> Dettagli Licenza</h5>
            <a href="<?= base_url('/licenze') ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Torna all'elenco
            </a>
        </div>

        <div class="card-body">
            <?php if (!empty($licenza)): ?>
                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Codice:</div>
                    <div class="col-sm-9"><?= esc($licenza->tblic_cd) ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Descrizione:</div>
                    <div class="col-sm-9"><?= esc($licenza->tblic_desc) ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Tipologia:</div>
                    <div class="col-sm-9"><?= esc($licenza->tblic_tp) ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3 fw-bold">Stato:</div>
                    <div class="col-sm-9">
                        <?php if ($licenza->tblic_stato === 't'): ?>
                            <span class="badge bg-success">Attiva</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Disattivata</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Licenza non trovata.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
