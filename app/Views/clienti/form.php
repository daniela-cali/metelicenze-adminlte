<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary  d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key-fill"></i> <?= esc($title) ?> </h5>
            <a href="<?= previous_url() ?>" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>

        <div class="card-body">
            <!--Aggiungo la modalità di creazione o modifica per il js-->
            <form action="<?= $action ?>" method="post" data-mode="<?= $mode ?>">
                <input type="hidden" name="backTo" value="<?= isset($backTo) ? esc($backTo) : '' ?>">

                <div class="mb-3">
                    <label for="codice" class="form-label">Codice Cliente</label>
                    <input type="text" name="codice" id="codice" class="form-control" required placeholder="Es. ABC12345"
                        value="<?= isset($cliente) ? esc($cliente["codice"]) : esc($internal_code) ?>">
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Ragione Sociale</label>
                    <input type="text" name="nome" id="nome" class="form-control" required placeholder="Rossi Srl"
                        value="<?= isset($cliente) ? esc($cliente["nome"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="piva" class="form-label">Partita IVA</label>
                    <input type="text" name="piva" id="piva" class="form-control" placeholder="IT12345678901"
                        value="<?= isset($cliente) ? esc($cliente["piva"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="indirizzo" class="form-label">Indirizzo</label>
                    <input type="text" name="indirizzo" id="indirizzo" class="form-control" required placeholder="Via Roma, 123"
                        value="<?= isset($cliente) ? esc($cliente["indirizzo"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="citta" class="form-label">Città</label>
                    <input type="text" name="citta" id="citta" class="form-control" required placeholder="Milano"
                        value="<?= isset($cliente) ? esc($cliente["citta"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="cap" class="form-label">CAP</label>
                    <input type="text" name="cap" id="cap" class="form-control" required placeholder="20100"
                        value="<?= isset($cliente) ? esc($cliente["cap"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" name="provincia" id="provincia" class="form-control" required placeholder=""
                        value="<?= isset($cliente) ? esc($cliente["provincia"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" placeholder="010.1234567"
                        value="<?= isset($cliente) ? esc($cliente["telefono"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="email@example.com"
                        value="<?= isset($cliente) ? esc($cliente["email"]) : '' ?>">
                </div>
                <div class="mb-3 form-check">
                    <input type="hidden" name="figlio_sn" value="0" />
                    <label 
                        for="figlio_sn"
                        class="form-check-label form-label">
                        Cliente interno 
                    </label>
                    <input type="hidden" name="figlio_sn" value="0" />
                    <input
                        type="checkbox"
                        class="form-check-input"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Cliente non presente nel gestionale esterno"
                        id="figlio_sn"
                        name="figlio_sn"
                        value="1"
                        <?= ((isset($cliente) && $cliente["figlio_sn"]) || ($mode == 'create')) ? 'checked' : '' ?>>
                    </input>
                </div>
                <div class="mb-3">
                    <label for="padre_id" class="form-label">Cliente Padre</label>
                    <select name="padre_id" id="padre_id" class="form-select" <?= ((isset($cliente) && $cliente["figlio_sn"]) || ($mode == 'create')) ? '' : 'disabled' ?> >
                        <option value="">-- Seleziona --</option>
                        <?php foreach ($selectValues as $option): ?>
                            <option value="<?= esc($option["value"]) ?>"
                                <?= (isset($cliente) && $cliente["padre_id"] == $option["value"]) ? 'selected' : '' ?>>
                                <?= esc($option["content"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class=" mb-3 form-group">
                    <label for="note">Note cliente</label>
                    <textarea class="form-control" id="note" name="note" rows="3"><?=
                    isset($cliente) ? esc($cliente["note"]) : ''
                    ?></textarea>
                </div>
                <div class=" mb-3 form-group">
                    <label for="contatti">Contatti</label>
                    <textarea class="form-control" id="contatti" name="contatti" rows="3"><?=
                    isset($cliente) ? esc($cliente["contatti"]) : ''
                    ?></textarea>
                </div>
                <div class="mb-3 form-check">
                    <label class="form-check-label" class="form-label" for="stato">Cliente attivo</label>
                    <!-- Per gestire il checkbox in modo che invii sempre un valore -->
                    <input type="hidden" name="stato" value="0" />
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="stato"
                        name="stato"
                        value="1"
                        <?php if (!isset($cliente) || $cliente["stato"]) echo 'checked'; ?> />
                </div>

                <div class="mt-4 " data-cliente="Common">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Salva
                    </button>
                    <a href="<?= previous_url() ?>" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inizializza il form con i dati della cliente se esistono
        const form = document.querySelector("form");
        const mode = form.dataset.mode || "edit"; // default "edit" se non esiste
        console.log("Modalità:", mode);

        if (mode === "view") {
            // Se la modalità è "view", rendo tutti i campi readonly/disabled
            form.querySelectorAll("input, select, textarea").forEach(el => {
                el.readOnly = true;
                el.disabled = true;
            });
            // Disabilito anche il bottone del form
            form.querySelectorAll('button[type="submit"]').forEach(btn => {
                btn.disabled = true;
            });
        }

        // Aggancio eventi alla checkbox figlio_sn
        const figlio_sn = document.getElementById("figlio_sn");
        console.log("Elemento figlio_sn:", figlio_sn);
        ["change", "load"].forEach(event => {
            figlio_sn.addEventListener(event, fn=> {
                if (figlio_sn.checked) {
                    console.log("Checkbox figlio_sn selezionata valore:", figlio_sn.value);
                    // Se è selezionato, abilito il padre_id
                    document.getElementById("padre_id").disabled = false;
                } else {
                    // Altrimenti lo disabilito
                    figlio_sn.value = 0; // Ripristino a 0 il valore
                    console.log("Checkbox figlio_sn deselezionata valore:", figlio_sn.value);
                    document.getElementById("padre_id").disabled = true;
                }
            });
        });
        
    });
</script>

<?= $this->endSection() ?>