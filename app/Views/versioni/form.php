<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('versioni_index') ?>">Versioni</a></li>
    <li class="breadcrumb-item active"><?= esc($title) ?></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$backTo = $backTo ?? back_to_url(base_url('/versioni'));
?>
<div class="card shadow-sm">
    <div class="card-header card-header-muted d-flex align-items-center">
        <h5 class="mb-0"><i class="bi bi-layers"></i> <?= esc($title) ?></h5>
        <a href="<?= esc($backTo) ?>" class="btn btn-light btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Indietro
        </a>
    </div>
    <div class="card-body">
        <form action="<?= $action ?>" method="post" data-mode="<?= $mode ?>">
            <input type="hidden" name="backTo" value="<?= esc($backTo) ?>">
            <div class="mb-3 form-check">
                <label class="form-check-label" class="form-label" for="ultima">Ultima Versione</label>
                <!-- Per gestire il checkbox in modo che invii sempre un valore -->
                <input type="hidden" name="ultima" value="0" />
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="ultima"
                    name="ultima"
                    value="1"
                    <?php if (!isset($versione) || $versione["ultima"]) echo 'checked'; ?> />
            </div>
            <input type="hidden" name="id" value="<?= isset($versione) ? esc($versione["id"]) : '' ?>">
            <div class="mb-3">
                <label for="dt_rilascio" class="form-label">Data Rilascio</label>
                <input type="date" name="dt_rilascio" id="dt_rilascio" class="form-control" required
                    value="<?= isset($versione) ? esc($versione["dt_rilascio"]) : '' ?>">
            </div>
            <div class="mb-3">
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="Common">-- Seleziona --</option>
                    <option value="Sigla" <?= (isset($versione) && $versione["tipo"] === 'Sigla') ? 'selected' : '' ?>>Sigla</option>
                    <option value="VarHub" <?= (isset($versione) && $versione["tipo"] === 'VarHub') ? 'selected' : '' ?>>VarHub</option>
                    <option value="SKNT" <?= (isset($versione) && $versione["tipo"] === 'SKNT') ? 'selected' : '' ?>>SKTN</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="codice" class="form-label">Codice</label>
                <input type="text" name="codice" id="codice" class="form-control" placeholder="Codice della versione" required
                    value="<?= isset($versione) ? esc($versione["codice"]) : '' ?>">
            </div>
            <div class="mb-3">
                <label for="release" class="form-label">Release della versione</label>
                <input type="text" name="release" id="release" class="form-control" placeholder="Release della versione" required
                    value="<?= isset($versione) ? esc($versione["release"]) : '' ?>">
            </div>
            <div class="mb-3">
                <label for="note_versione" class="form-label">Note di versione</label>
                <textarea name="note_versione" id="note_versione" class="form-control" rows="10"><?=
                    isset($versione) ? esc($versione["note_versione"]) : ''
                ?></textarea>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Salva
                </button>
                <a href="<?= esc($backTo) ?>" class="btn btn-secondary">Annulla</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inizializza il form con i dati se esistono
        const mode = $('form').data('mode');
        console.log('Modalità: ' + mode);
        if (mode === 'view') {
            // Se la modalità è "view", rendo tutti i campi readonly
            $('input, select, textarea').prop('readonly', true).prop('disabled', true);
            // Disabilito anche il bottone del form
            $('button[type="submit"]').prop('disabled', true);
        }
    });
</script>
<?= $this->endSection() ?>
