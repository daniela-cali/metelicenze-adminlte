<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('tipi_index') ?>">Tipi Licenze</a></li>
    <li class="breadcrumb-item active"><?= esc($title) ?></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$backTo = $backTo ?? base_url('/tipi');
?>
<div class="card shadow-sm">
    <div class="card-header card-header-muted d-flex align-items-center">
        <h5 class="mb-0"><i class="bi bi-tags"></i> <?= esc($title) ?></h5>
        <a href="<?= esc($backTo) ?>" class="btn btn-light btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Indietro
        </a>
    </div>

        <div class="card-body">
            <form action="<?= $form["action"] ?>" method="POST" data-mode="<?= $mode ?>">
                <input type="hidden" name="_method" value="<?= $mode === 'edit' ? 'PUT' : 'POST' ?>">
                <input type="hidden" name="backTo" value="<?= esc($backTo) ?>">

                                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select name="categoria" id="categoria" class="form-select" required>
                        <option value="Common">-- Seleziona --</option>
                        <option value="gest_contab" <?= (isset($tipoLicenza) && $tipoLicenza["categoria"] === 'gest_contab') ? 'selected' : '' ?>>Gestionale Contabile</option>
                        <option value="fatt_elett" <?= (isset($tipoLicenza) && $tipoLicenza["categoria"] === 'fatt_elett') ? 'selected' : '' ?>>Fatturazione Elettronica</option>
                        <option value="firma_digitale" <?= (isset($tipoLicenza) && $tipoLicenza["categoria"] === 'firma_digitale') ? 'selected' : '' ?>> Servizio di Firma Digitale</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" value="<?= isset($tipoLicenza) ? esc($tipoLicenza['nome']) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="descrizione" class="form-label">Descrizione</label>
                    <textarea rows="5" name="descrizione" id="descrizione" class="form-control"><?= isset($tipoLicenza) ? esc($tipoLicenza['descrizione']) : '' ?></textarea>
                </div>
                <div class="mb-3 form-check">
                    <label class="form-check-label" class="form-label" for="stato">Attivo</label>
                    <!-- Per gestire il checkbox in modo che invii sempre un valore -->
                    <input type="hidden" name="stato" value="0" />
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="stato"
                        name="stato"
                        value="1"
                        <?php if (!isset($tipoLicenza) || $tipoLicenza["stato"]) echo 'checked'; ?> />
                </div>
 
                <div class="mt-4 ">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> <?= esc($form['submitText']) ?>
                    </button>
                    <a href="<?= esc($backTo) ?>" class="btn btn-secondary">Annulla</a>
                </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

