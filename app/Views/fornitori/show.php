<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4 mb-4" id="scheda-fornitore">

    <div class="container-fluid mt-4 p-0">
        <h1 class="display-6 mb-2">
            <i class="bi bi-people-fill"></i> Scheda Fornitore
        </h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="lead mb-0">
                Dettagli e gestione del fornitore
            </p>

            <a href="<?= site_url('fornitori/') ?>"
                class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Torna all'elenco fornitori
            </a>
        </div>
    </div>
    <nav id="anchor-nav" class="navbar navbar-expand-lg navbar-light bg-light anchor-nav rounded shadow-sm mt-3 mb-3 p-2">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#anagrafica">Anagrafica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#licenze">Tipi Licenze Fornite</a>
                </li>

            </ul>
            <ul class="navbar-nav ms-auto">
                <li>
                    <a href="<?= previous_url() ?>" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left-circle"></i> Torna indietro
                    </a>
                </li>
            </ul>

        </div>
    </nav>
</div>

</div>

<!--Card Anagrafica-->
<div class="container-fluid mt-4 p-0" id="anagrafica">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Dati Anagrafici</h5>
            <a href="<?= url_to('fornitori_edit', $fornitore["id"]) ?>" class="btn btn-light btn-outline-secondary btn-sm" title="Modifica">
                Modifica <i class="bi bi-pencil"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <!-- COLONNA SINISTRA -->
                    <dl class="dl-kv">
                        <dt>Codice Fornitore</dt>
                        <dd><?= esc($fornitore["codice"]) ?></dd>

                        <dt>Ragione Sociale</dt>
                        <dd><?= esc($fornitore["nome"]) ?></dd>

                        <dt>Partita IVA</dt>
                        <dd class=><?= esc($fornitore["piva"]) ?></dd>
                        <dt>Indirizzo</dt>
                        <dd class=><?= esc($fornitore["indirizzo"]) ?></dd>

                        <dt>CAP</dt>
                        <dd class=><?= esc($fornitore["cap"]) ?></dd>

                        <dt>Città</dt>
                        <dd class=><?= esc($fornitore["citta"]) ?></dd>

                        <dt>Provincia</dt>
                        <dd class=><?= esc($fornitore["provincia"]) ?></dd>
                    </dl>
                </div>
                <!-- COLONNA DESTRA -->
                <div class="col-12 col-md-6">
                    <dl class="dl-kv">
                        <dt>Email</dt>
                        <dd class=><?= esc($fornitore["email"]) ?></dd>

                        <dt>Telefono</dt>
                        <dd class=><?= esc($fornitore["telefono"]) ?></dd>

                        <dt>Contatti</dt>
                        <dd class=><?= esc($fornitore["contatti"]) ?></dd>

                        <dt>Note</dt>
                        <dd class=><?= esc($fornitore["note"]) ?></dd>

                </div>

            </div>
            <a href="#scheda-fornitore" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-up-square"></i> Torna in cima
            </a>
        </div> <!--Fine Card Body Anagrafica-->
    </div> <!--Fine Card Anagrafica-->
</div> <!--Fine Container Anagrafica-->



<!--Card Tipi Licenze-->
<div class="container-fluid mt-4 p-0" id="licenze">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key-fill"></i> Tipi Licenze fornite</h5>
            <a href="<?= url_to('tipi_link', $fornitore["id"]) ?>" class="btn btn-light btn-outline-secondary btn-sm" title="Associa Nuova tipologia per il fornitore" data-bs-toggle="modal" data-bs-target="#TipiModal">
                Associa Tipo Licenza <i class="bi bi-link"></i>
            </a>

        </div>
        <div class="card-body">
            <?php if (!empty($tipiLicenze)): ?>
                <table class="table table-bordered table-striped table-hover align-middle datatable" id="tabella-licenze">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Codice</th>
                            <th>Tipo</th>
                            <th>Modello</th>
                            <th>Stato</th>
                            <th class="notexport">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tipiLicenze as $tipo): ?>
                            <tr class="licenza-row" data-id="<?= esc($tipo["id"])
                                                                ?>" style="cursor:pointer;">
                                <td><?= esc($tipo["id"]) ?></td>
                                <td><?= $tipo["codice"] ? esc($tipo["codice"]) : esc($tipo["ambiente"]) ?></td>
                                <td><?= esc($tipo["tipo"]) ?></td>
                                <td><?= esc($tipo["modello"]) ?></td>
                                <td>
                                    <span class="badge <?= $tipo["stato"] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $tipo["stato"] ? 'Attiva' : 'Inattiva' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/aggiornamenti/crea/<?= $tipo["id"] ?>/<?= $tipo["tipo"] ?>" 
                                        class="btn btn-sm btn-outline-primary" 
                                        title="Crea Aggiornamento">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    <a href="/licenze/modifica/<?= $tipo["id"] ?>" 
                                        class="btn btn-sm btn-outline-secondary" 
                                        title="Modifica">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/licenze/elimina/<?= $tipo["id"]?>" 
                                        class="btn btn-sm btn-outline-danger"
                                        title="Elimina" 
                                        onclick=" return confirm('Sei sicuro di voler eliminare questa licenza?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Nessuna tipologia di licenza associata a questo fornitore.
                </div>
            <?php endif; ?>
            <a href="#scheda-fornitore" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-up-square"></i> Torna in cima
            </a>
        </div>
    </div>
</div> <!--Fine Card Tipi Licenze-->
<!--Modal link alle tipologie di licenze-->
<div class="modal fade" id="TipiModal" tabindex="-1" aria-labelledby="TipiModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TipiModal">Associa Nuova Tipologia di Licenza</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= url_to('tipi_link', $fornitore["id"]) ?>" method="POST">
                    <input type="hidden" name="backTo" value="<?= url_to('fornitori_show', $fornitore["id"]) ?>">
                    <select name="id_licenza" id="id_licenza" class="form-select" required>
                        <?php foreach ($selectData as $option): ?>
                            <option value="<?= esc($option["id"]) ?>">
                                <?= esc($option["value"]) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Associa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php $this->endSection(); ?>