<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary  d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key-fill"></i> <?= esc($title) ?> </h5>
            <a href="<?= $backTo ?>" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>

        <div class="card-body">
            <!--Aggiungo la modalità di creazione o modifica per il js-->
            <form action="<?= $action ?>" method="post" data-mode="<?= $mode ?>">
                <input type="hidden" name="id" value="<?= isset($aggiornamento->id) ? esc($aggiornamento->id) : '' ?>">

                <div class="mb-3">
                    <label for="dt_agg" class="form-label">Data Aggiornamento</label>
                    <input type="date" name="dt_agg" id="dt_agg" class="form-control" required 
                    value="<?= isset($aggiornamento) ? esc($aggiornamento->dt_agg) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="versioni_id" class="form-label">Versione</label>
                    <select name="versioni_id" id="versioni_id" class="form-select" required>
                        <option value="">-- Seleziona --</option>
                        <?php foreach ($versioni as $v): ?>
                            <option value="<?= esc($v->id) ?>" <?= (isset($aggiornamento) && $aggiornamento->versioni_id === $v->id) ? 'selected' : '' ?>>
                                <?= esc($v->codice) ?> - <?= esc($v->release) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Note sull'aggiornamento</label>
                    <textarea name="note" id="note" class="form-control" rows="10"><?= 
                        isset($aggiornamento) ? esc($aggiornamento->note) : '' 
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
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inizializza il form con i dati della licenza se esistono
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