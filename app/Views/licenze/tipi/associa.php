<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary  d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key-fill"></i> <?= esc($title) ?> </h5>
            <a href="<?= previous_url() ?>" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>

        <div class="card-body">
            <!--Aggiungo la modalità di creazione o modifica per il js-->
            <form action="<?= $action ?>" method="post" data-mode="<?= $mode ?>">
                <input type="hidden" name="backTo" value="<?= isset($backTo) ? esc($backTo) : '' ?>">

                <div class="mb-3" >
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="Common">-- Seleziona --</option>
                        <option value="Sigla" <?= (isset($tplicenza) && $tplicenza["tipo"] === 'Sigla') ? 'selected' : '' ?>>Sigla</option>
                        <option value="VarHub" <?= (isset($tplicenza) && $tplicenza["tipo"] === 'VarHub') ? 'selected' : '' ?>>VarHub</option>
                        <option value="Servizio Firma" <?= (isset($tplicenza) && $tplicenza["tipo"] === 'SKNT') ? 'selected' : '' ?>>SKTN</option>
                    </select>
                </div>

 
                </div>
                

                <div class="mt-4 " >
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
        let licenzePadre = <?= isset($licenzePadre) ? json_encode($licenzePadre) : 'null' ?>;

        console.log("Licenze del padre:", licenzePadre);
        // Inizializza il form con i dati della licenza se esistono
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

            document.querySelectorAll("[data-licenza]").forEach(wrapper => {
                const valoreWrapper = wrapper.getAttribute("data-licenza");
                console.log("Wrapper con data-licenza:", valoreWrapper);

                if (valoreWrapper.includes(tipoSelezionato) || valoreWrapper === "Common") {
                    wrapper.classList.remove("d-none");

                    // Rendo i campi all'interno del wrapper obbligatori (se non in view)
                    if (mode !== "view") {
                        wrapper.querySelectorAll("input, select, textarea").forEach(campo => {});
                    }
                } else {
                    wrapper.classList.add("d-none");
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
            //Listener
            //console.log("Aggiungo event listener per:", event);
            selectTipo.addEventListener(event, aggiornaCampi);
            //Suggerimento licenza padre
            selectTipo.addEventListener(event, function() {
                if (licenzePadre) {
                    const tipoSelezionato = selectTipo.value;
                    let suggerimento = licenzePadre[tipoSelezionato];
                    //console.log("Suggerimento licenza padre per tipo " + tipoSelezionato + ":" + suggerimento + 'ID: ' +  suggerimento.id);
                    if (suggerimento && tipoSelezionato === "Sigla") 
                    {
                        //console.log("Popolo i campi con il suggerimento della licenza padre Sigla");
                        document.getElementById("padre_lic_id").value = suggerimento.id;
                        document.getElementById("codice").value = suggerimento.codice;
                        document.getElementById("postazioni").value = suggerimento.postazioni;
                        document.getElementById("modello").value = suggerimento.modello;
                        document.getElementById("conn").value = suggerimento.conn;
                        document.getElementById("server").value = suggerimento.server;
                        document.getElementById("note").value = suggerimento.note;
                        document.getElementById("figlio_sn").checked = true; // Imposto come figlio
                        document.getElementById("figlio_sn").value = 1; 
                        document.getElementById("stato").checked = suggerimento.stato == 1 ? true : false;
                    }
                }
                
            });
        });

        // Eseguo subito una prima volta per lo stato iniziale
        aggiornaCampi();
    });
</script>

<?= $this->endSection() ?>