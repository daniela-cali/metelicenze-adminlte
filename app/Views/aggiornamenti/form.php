<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active"><?= esc($title) ?></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header card-header-muted d-flex align-items-center">
        <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> <?= esc($title) ?></h5>
        <a href="<?= esc($backTo) ?>" class="btn btn-light btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Indietro
        </a>
    </div>

        <div class="card-body">
            <!--Aggiungo la modalità di creazione o modifica per il js-->
            <form action="<?= $form['action'] ?>" method="<?= $form['method'] ?>" data-mode="<?= $mode ?>">
                <input type="hidden" name="backTo" value="<?= esc($backTo) ?>">
                <input type="hidden" name="id" value="<?= isset($aggiornamento->id) ? esc($aggiornamento->id) : '' ?>">

                <div class="mb-3">
                    <label for="dt_agg" class="form-label">Data Aggiornamento</label>
                    <input type="date" name="dt_agg" id="dt_agg" class="form-control" required 
                    value="<?= isset($aggiornamento) ? esc($aggiornamento['dt_agg']) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="versioni_id" class="form-label">Versione</label>
                    <select name="versioni_id" id="versioni_id" class="form-select" required>
                        <option value="">-- Seleziona --</option>
                        <?php foreach ($versioni as $v): ?>
                            <option value="<?= esc($v["id"]) ?>" <?= (isset($aggiornamento) && $aggiornamento['versioni_id'] === $v["id"]) ? 'selected' : '' ?>>
                                <?= esc($v["codice"]) ?> - <?= esc($v["release"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Note sull'aggiornamento</label>
                    <textarea name="note" id="note" class="form-control" rows="10"><?= 
                        isset($aggiornamento) ? esc($aggiornamento['note']) : '' 
                    ?></textarea>
                </div>
                <div class="mt-4">

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Salva
                    </button>
                    <a href="<?= $backTo ?>" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
</div>
<?= $this->endSection() ?>
<?= $this->endSection() ?>