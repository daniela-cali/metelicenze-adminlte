<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary  d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key-fill"></i> <?= esc($title) ?> </h5>
            <a href="<?= isset($backTo) ? esc($backTo) : url_to('tipi_index') ?>" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select name="categoria" id="categoria" class="form-select" disabled>
                    <option value="Common">-- Seleziona --</option>
                    <option value="gest_contab" <?= (isset($tipo) && $tipo["categoria"] === 'gest_contab') ? 'selected' : '' ?>>Gestionale Contabile</option>
                    <option value="fatt_elett" <?= (isset($tipo) && $tipo["categoria"] === 'fatt_elett') ? 'selected' : '' ?>>Fatturazione Elettronica</option>
                    <option value="firma_digitale" <?= (isset($tipo) && $tipo["categoria"] === 'firma_digitale') ? 'selected' : '' ?>> Servizio di Firma Digitale</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?= isset($tipo) ? esc($tipo['nome']) : '' ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="descrizione" class="form-label">Descrizione</label>
                <input type="text" name="descrizione" id="descrizione" class="form-control" value="<?= isset($tipo) ? esc($tipo['descrizione']) : '' ?>" readonly>
            </div>
                <div class="mb-3 form-check">
                    <label class="form-check-label" class="form-label" for="stato">Attivo</label>
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="stato"
                        name="stato"
                        value="1"
                        disabled
                        <?php if (!isset($tipoLicenza) || $tipoLicenza["stato"]) echo 'checked'; ?> />
                </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>