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

                <div class="mb-3" data-licenza="Common">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="Common">-- Seleziona --</option>
                        <option value="Sigla" <?= (isset($licenza) && $licenza["tipo"] === 'Sigla') ? 'selected' : '' ?>>Sigla</option>
                        <option value="VarHub" <?= (isset($licenza) && $licenza["tipo"] === 'VarHub') ? 'selected' : '' ?>>VarHub</option>
                        <option value="SKNT" <?= (isset($licenza) && $licenza["tipo"] === 'SKNT') ? 'selected' : '' ?>>SKTN</option>
                    </select>
                </div>

                <input type="hidden" name="padre_lic_id" id="padre_lic_id" value="<?= isset($licenza["padre_lic_id"]) ? esc($licenza["padre_lic_id"]) : '' ?>">
                
                <div class="mb-3 d-none" data-licenza="Sigla,SKNT">
                    <label for="codice" class="form-label">Codice Licenza</label>
                    <input type="text" name="codice" id="codice" class="form-control" required placeholder="Es. ABC12345"
                        value="<?= isset($licenza) ? esc($licenza["codice"]) : '' ?>">
                </div>
                <div class="mb-3 d-none" data-licenza="VarHub">
                    <label for="ambiente" class="form-label">Ambiente</label>
                    <input type="text" name="ambiente" id="ambiente" class="form-control" required placeholder="Es. usr_12345"
                        value="<?= isset($licenza) ? esc($licenza["ambiente"]) : '' ?>">
                </div>
                <div class="mb-3 d-none" data-licenza="VarHub">
                    <label for="nodo" class="form-label">Nodo</label>
                    <input type="text" name="nodo" id="nodo" class="form-control" required placeholder="CLNT_12345XYZ"
                        value="<?= isset($licenza) ? esc($licenza["nodo"]) : '' ?>">
                </div>
                <div class="mb-3 d-none" data-licenza="VarHub">
                    <label for="invii" class="form-label">Invii</label>
                    <input type="text" name="invii" id="invii" class="form-control" required placeholder="500"
                        value="<?= isset($licenza) ? esc($licenza["invii"]) : 500 ?>">
                </div>
                <div class="mb-3 d-none" data-licenza="VarHub">
                    <label for="giga" class="form-label">Giga</label>
                    <input type="text" name="giga" id="giga" class="form-control" required placeholder="Invii * 2"
                        value="<?= isset($licenza) ? esc($licenza["giga"]) : '' ?>">
                </div>
                <div class="mb-3 d-none" data-licenza="Sigla">
                    <label for="modello" class="form-label" data-licenza="Sigla">Modello</label>
                    <select name="modello" id="modello" class="form-select" required>
                        <option value="Common">-- Seleziona --</option>
                        <option value="Start" <?= (isset($licenza) && $licenza["modello"] === 'Start') ? 'selected' : '' ?>>Start</option>
                        <option value="Ultimate" <?= (isset($licenza) && $licenza["modello"] === 'Ultimate') ? 'selected' : '' ?>>Ultimate</option>
                        <option value="Cloud" <?= (isset($licenza) && $licenza["modello"] === 'Cloud') ? 'selected' : '' ?>>Cloud</option>
                        <option value="N/A" <?= (isset($licenza) && empty($licenza["modello"])) ? 'selected' : '' ?>>Nessun tipo di modello</option>
                    </select>
                </div>
                <div class="mb-3 d-none" data-licenza="Sigla">
                    <label for="postazioni" class="form-label">Postazioni Licenza</label>
                    <input type="text" name="postazioni" id="postazioni" class="form-control" required placeholder="1"
                        value="<?= isset($licenza) ? esc($licenza["postazioni"]) : 1 ?>" />
                </div>
                <div class="mb-3 form-check d-none" data-licenza="Sigla">
                    <input type="hidden" name="figlio_sn" value="0" />
                    <label class="form-check-label" class="form-label" for="stato"><i>Licenza Figlio</i></label>
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="figlio_sn"
                        name="figlio_sn"
                        value = "1"
                        <?= (isset($licenza) && $licenza["figlio_sn"]) ? 'checked' : '' ?>>
                    </input>
                </div>
                <div class=" mb-3 form-group d-none" data-licenza="Common">
                    <label for="server">Server</label>
                    <input type="text" class="form-control" id="server" name="server" placeholder="192.168.0.1, localhost, ecc." value="<?= isset($licenza) ? esc($licenza["server"]) : '' ?>">
                    </input>
                </div>
                <div class=" mb-3 form-group d-none" data-licenza="Sigla">
                    <label for="conn">Connessioni</label>
                    <textarea class="form-control" id="conn" name="conn" rows="3"><?=
                        isset($licenza) ? esc($licenza["conn"]) : ''
                    ?></textarea>
                </div>

                <div class=" mb-3 form-group" data-licenza="Common">
                    <label for="note">Note Licenza</label>
                    <textarea class="form-control" id="note" name="note" rows="3"><?=
                        isset($licenza) ? esc($licenza["note"]) : ''
                    ?></textarea>
                </div>
                <div class="mb-3 form-check" data-licenza="Common">
                    <label class="form-check-label" class="form-label" for="stato">Licenza attiva</label>
                    <!-- Per gestire il checkbox in modo che invii sempre un valore -->
                    <input type="hidden" name="stato" value="0"/>
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="stato"
                        name="stato"
                        value="1"
                        <?php if (!isset($licenza) || $licenza["stato"]) echo 'checked'; ?> />
                </div>

                <div class="mt-4 " data-licenza="Common">
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