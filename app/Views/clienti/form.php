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
                        value="<?= isset($cliente) ? esc($cliente->codice) : esc($internal_code) ?>">
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Ragione Sociale</label>
                    <input type="text" name="nome" id="nome" class="form-control" required placeholder="Rossi Srl"
                        value="<?= isset($cliente) ? esc($cliente->nome) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="piva" class="form-label">Partita IVA</label>
                    <input type="text" name="piva" id="piva" class="form-control" placeholder="IT12345678901"
                        value="<?= isset($cliente) ? esc($cliente->piva) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="indirizzo" class="form-label">Indirizzo</label>
                    <input type="text" name="indirizzo" id="indirizzo" class="form-control" required placeholder="Via Roma, 123"
                        value="<?= isset($cliente) ? esc($cliente->indirizzo) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="citta" class="form-label">Città</label>
                    <input type="text" name="citta" id="citta" class="form-control" required placeholder="Milano"
                        value="<?= isset($cliente) ? esc($cliente->citta) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="cap" class="form-label">CAP</label>
                    <input type="text" name="cap" id="cap" class="form-control" required placeholder="20100"
                        value="<?= isset($cliente) ? esc($cliente->cap) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" name="provincia" id="provincia" class="form-control" required placeholder=""
                        value="<?= isset($cliente) ? esc($cliente->provincia) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" placeholder="010.1234567"
                        value="<?= isset($cliente) ? esc($cliente->telefono) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="email@example.com"
                        value="<?= isset($cliente) ? esc($cliente->email) : '' ?>">
                </div>
                <div class="mb-3 form-check">
                    <input type="hidden" name="figlio_sn" value="0" />
                    <label 
                        for="figlio_sn"
                        class="form-check-label form-label">
                        Cliente interno 
                    </label>
                    <input
                        type="checkbox"
                        class="form-check-input"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Cliente non presente nel gestionale esterno"
                        id="figlio_sn"
                        name="figlio_sn"
                        <?= ((isset($cliente) && $cliente->figlio_sn) || ($mode == 'create')) ? 'checked' : '' ?>>
                    </input>
                </div>
                <div class=" mb-3 form-group">
                    <label for="note">Note cliente</label>
                    <textarea class="form-control" id="note" name="note" rows="3"><?=
                    isset($cliente) ? esc($cliente->note) : ''
                    ?></textarea>
                </div>
                <div class=" mb-3 form-group">
                    <label for="contatti">Contatti</label>
                    <textarea class="form-control" id="contatti" name="contatti" rows="3"><?=
                    isset($cliente) ? esc($cliente->contatti) : ''
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
                        <?php if (!isset($cliente) || $cliente->stato) echo 'checked'; ?> />
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

        // Funzione per aggiornare la visibilità in base al tipo selezionato
        function aggiornaCampi() {
            const tipoSelezionato = selectTipo.value;
            console.log("Tipo selezionato:", tipoSelezionato);

            document.querySelectorAll("[data-cliente]").forEach(wrapper => {
                const valoreWrapper = wrapper.getAttribute("data-cliente");
                console.log("Wrapper con data-cliente:", valoreWrapper);

                if (valoreWrapper.includes(tipoSelezionato) || valoreWrapper === "Common") {
                    wrapper.classList.remove("");

                    // Rendo i campi all'interno del wrapper obbligatori (se non in view)
                    if (mode !== "view") {
                        wrapper.querySelectorAll("input, select, textarea").forEach(campo => {});
                    }
                } else {
                    wrapper.classList.add("");
                    // Rimuovo obbligatorietà e resetto i valori
                    wrapper.querySelectorAll("input, select, textarea").forEach(campo => {
                        campo.required = false;
                        campo.value = "";
                    });
                }
            });
        }

        // Aggancio eventi al select
        const selectTipo = document.getElementById("tipo");
        ["change", "load"].forEach(event => {
            console.log("Aggiungo event listener per:", event);
            selectTipo.addEventListener(event, aggiornaCampi);
        });

        // Eseguo subito una prima volta per lo stato iniziale
        aggiornaCampi();
    });
</script>

<?= $this->endSection() ?>