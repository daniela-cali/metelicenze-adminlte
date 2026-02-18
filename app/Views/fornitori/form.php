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
            <form action="<?= $form['action'] ?>" method="<?= $form['method'] ?>" data-mode="<?= $mode ?>">
                <input type="hidden" name="_method" value="<?= $form['spoof'] ?>">
                <input type="hidden" name="backTo" value="<?= isset($backTo) ? esc($backTo) : '' ?>">
                

                <div class="mb-3">
                    <label for="codice" class="form-label">Codice Fornitore</label>
                    <input type="text" 
                        name="codice" 
                        id="codice" 
                        class="form-control" 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="Codice fornitore utilizzato per identificare univocamente il fornitore su database esterni."
                        required placeholder="Es. ABC12345"

                        value="<?= isset($fornitore) ? esc($fornitore["codice"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="id_external" class="form-label">ID Esterno Fornitore</label>
                    <input type="text" 
                        name="id_external" 
                        id="id_external" 
                        class="form-control" 
                        required placeholder="Es. 12345"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="ID univoco utilizzato per identificare il fornitore su database esterni."
                        value="<?= isset($fornitore) ? esc($fornitore["id_external"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Ragione Sociale</label>
                    <input type="text" name="nome" id="nome" class="form-control" required placeholder="Rossi Srl"
                        value="<?= isset($fornitore) ? esc($fornitore["nome"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="piva" class="form-label">Partita IVA</label>
                    <input type="text" name="piva" id="piva" class="form-control" placeholder="IT12345678901"
                        value="<?= isset($fornitore) ? esc($fornitore["piva"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="indirizzo" class="form-label">Indirizzo</label>
                    <input type="text" name="indirizzo" id="indirizzo" class="form-control" required placeholder="Via Roma, 123"
                        value="<?= isset($fornitore) ? esc($fornitore["indirizzo"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="citta" class="form-label">Città</label>
                    <input type="text" name="citta" id="citta" class="form-control" required placeholder="Milano"
                        value="<?= isset($fornitore) ? esc($fornitore["citta"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="cap" class="form-label">CAP</label>
                    <input type="text" name="cap" id="cap" class="form-control" required placeholder="20100"
                        value="<?= isset($fornitore) ? esc($fornitore["cap"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="provincia" class="form-label">Provincia</label>
                    <input type="text" name="provincia" id="provincia" class="form-control" required placeholder=""
                        value="<?= isset($fornitore) ? esc($fornitore["provincia"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" placeholder="010.1234567"
                        value="<?= isset($fornitore) ? esc($fornitore["telefono"]) : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="email@example.com"
                        value="<?= isset($fornitore) ? esc($fornitore["email"]) : '' ?>">
                </div>
 

                <div class=" mb-3 form-group">
                    <label for="note">Note fornitore</label>
                    <textarea class="form-control" id="note" name="note" rows="3"><?=
                    isset($fornitore) ? esc($fornitore["note"]) : ''
                    ?></textarea>
                </div>
                <div class=" mb-3 form-group">
                    <label for="contatti">Contatti</label>
                    <textarea class="form-control" id="contatti" name="contatti" rows="3"><?=
                    isset($fornitore) ? esc($fornitore["contatti"]) : ''
                    ?></textarea>
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
                        <?php if (!isset($fornitore) || $fornitore["stato"]) echo 'checked'; ?> />
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> <?= $form['submitText'] ?>
                    </button>
                    <a href="<?= previous_url() ?>" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
