<?php $this->extend('layouts/main'); ?>

<?php $this->section('breadcrumb'); ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('fornitori_index') ?>">Fornitori</a></li>
    <li class="breadcrumb-item active">Scheda Fornitore</li>
</ol>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>

<div id="scheda-fornitore">

    <div class="d-flex align-items-center mb-3">
        <p class="lead mb-0">Dettagli e gestione del fornitore</p>
        <a href="<?= url_to('fornitori_index') ?>" class="btn btn-light btn-outline-secondary btn-sm ms-auto">
            <i class="bi bi-arrow-left-circle"></i> Torna all'elenco fornitori
        </a>
    </div>

    <nav id="anchor-nav" class="navbar navbar-expand-lg navbar-light bg-light anchor-nav rounded shadow-sm mt-3 mb-3 p-2">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#anagrafica">Anagrafica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#licenze">Tipi Licenze Fornite</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li>
                    <a href="<?= esc($backTo) ?>" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left-circle"></i> Torna indietro
                    </a>
                </li>
            </ul>
        </div>
    </nav>

</div>

<!--Card Anagrafica-->
<div class="container-fluid mt-4 p-0" id="anagrafica">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Dati Anagrafici</h5>
            <a href="<?= url_to('fornitori_edit', $fornitore["id"]) ?>" class="btn btn-light btn-outline-secondary btn-sm ms-auto" title="Modifica">
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
                        <dd><?= esc($fornitore["piva"]) ?></dd>

                        <dt>Indirizzo</dt>
                        <dd><?= esc($fornitore["indirizzo"]) ?></dd>

                        <dt>CAP</dt>
                        <dd><?= esc($fornitore["cap"]) ?></dd>

                        <dt>Città</dt>
                        <dd><?= esc($fornitore["citta"]) ?></dd>

                        <dt>Provincia</dt>
                        <dd><?= esc($fornitore["provincia"]) ?></dd>
                    </dl>
                </div>
                <!-- COLONNA DESTRA -->
                <div class="col-12 col-md-6">
                    <dl class="dl-kv">
                        <dt>Email</dt>
                        <dd><?= esc($fornitore["email"]) ?></dd>

                        <dt>Telefono</dt>
                        <dd><?= esc($fornitore["telefono"]) ?></dd>

                        <dt>Contatti</dt>
                        <dd><?= esc($fornitore["contatti"]) ?></dd>

                        <dt>Note</dt>
                        <dd><?= esc($fornitore["note"]) ?></dd>
                    </dl>
                </div>
            </div>
            <a href="#scheda-fornitore" class="btn btn-light btn-outline-secondary btn-sm mt-3">
                <i class="bi bi-arrow-up-square"></i> Torna in cima
            </a>
        </div>
    </div>
</div>

<!--Card Tipi Licenze-->
<div class="container-fluid mt-4 p-0" id="licenze">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0"><i class="bi bi-key-fill"></i> Tipi Licenze fornite</h5>
            <a href="<?= url_to('tipilicenze_link', $fornitore["id"]) ?>" class="btn btn-light btn-outline-secondary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#TipiModal">
                Associa Tipo Licenza <i class="bi bi-link"></i>
            </a>
        </div>
        <div class="card-body">
            <?php if (!empty($licenzeFornite)): ?>
                <table class="table table-bordered table-striped table-hover align-middle datatable" id="primaryTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrizione</th>
                            <th>Categoria</th>
                            <th>Stato</th>
                            <th class="notexport">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($licenzeFornite as $tipo): ?>
                            <tr class="data-row"
                                data-id="<?= esc($tipo["id"]) ?>"
                                style="cursor:pointer;"
                                <?= audit_tooltip($tipo, 'right') ?>>
                                <td><?= esc($tipo["id"]) ?></td>
                                <td><?= esc($tipo["nome"]) ?></td>
                                <td><?= esc($tipo["modello"]) ?></td>
                                <td><?= esc($tipo["descrizione"]) ?></td>
                                <td><?= esc($tipo["categoria_label"]) ?></td>
                                <td>
                                    <span class="badge <?= $tipo["stato"] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $tipo["stato"] ? 'Attiva' : 'Inattiva' ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn dropdown-toggle" type="button" id="azione-<?= $tipo['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-list"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="azione-<?= $tipo['id'] ?>">
                                        <li>
                                            <a class="dropdown-item" href="<?= url_to('tipilicenze_edit', $tipo["id"]) ?>">
                                                <i class="bi bi-pencil"></i> Modifica
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= url_to('tipilicenze_unlink', $tipo["id"]) ?>" onclick="return confirm('Rimuovere questa tipologia dal fornitore?');">
                                                <i class="bi bi-link-45deg"></i> Scollega
                                            </a>
                                        </li>
                                    </ul>
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
            <a href="#scheda-fornitore" class="btn btn-light btn-outline-secondary btn-sm mt-3">
                <i class="bi bi-arrow-up-square"></i> Torna in cima
            </a>
        </div>
    </div>
</div>

<!--Modal associa tipo licenza-->
<div class="modal fade" id="TipiModal" tabindex="-1" aria-labelledby="TipiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TipiModalLabel">Associa Nuova Tipologia di Licenza</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url_to('tipilicenze_link', $fornitore["id"]) ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="backTo" value="<?= url_to('fornitori_show', $fornitore["id"]) ?>">
                    <select name="id_licenza" id="id_licenza" class="form-select" required>
                        <?php foreach ($selectData as $option): ?>
                            <option value="<?= esc($option["id"]) ?>"><?= esc($option["value"]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary">Associa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableRows = document.querySelectorAll('.data-row');
        const baseUrl   = "<?= base_url() ?>";

        tableRows.forEach(row => {
            row.addEventListener('click', function() {
                tableRows.forEach(r => r.classList.remove('table-primary', 'selected'));
                this.classList.add('table-primary', 'selected');
            });
            row.addEventListener('dblclick', function() {
                const id = this.getAttribute('data-id');
                window.location.href = `${baseUrl}/tipilicenze/${id}`;
            });
        });
    });
</script>
<?php $this->endSection(); ?>
