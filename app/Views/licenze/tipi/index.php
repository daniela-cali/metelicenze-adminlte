<?php $this->extend('layouts/main') ?>
<?php $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key"></i> Elenco Tipi Licenze</h5>
<a href="<?= url_to('tipi_new') ?>" class="btn btn-light btn-outline-secondary btn-sm" title="Aggiungi nuovo tipo di licenza">
                <i class="bi bi-plus-circle"></i>
                Nuovo tipo di licenza
            </a>
            </a>
        </div>
        <div class="card-body">
 
            <?php if (!empty($tipiLicenze)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="tipiTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID Tipo</th>
                                <th>Codice</th>
                                <th>Tipo</th>
                                <th class="notexport">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tipiLicenze as $tipo): ?>
                                <?php $trClass = $tipo["stato"] == 0 ? 'table-danger' : ''; ?>
                                <tr class="clickable <?= $trClass ?>" 
                                data-id="<?= esc($tipo["id"])?>" 
                                data-bs-toggle="tooltip"
                                data-bs-placement= "right"
                                title="Creato da: <?= $tipo["created_by_name"] ?> il <?= date('d/m/Y H:i', strtotime($tipo["created_at"])) ?>">
                                    <td><?= esc($tipo["id"]) ?></td>
                                    <td><?= esc($tipo["nome"]) ?></td>
                                    <td><?= esc($tipo["categoria_nome"]) ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle" type="button" id="azione" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-list"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                <li>
                                                    <a class="dropdown-item" href="<?= url_to('tipi_show', $tipo["id"]) ?>">
                                                        <i class="bi bi-eye"></i>
                                                        Visualizza
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?= url_to('tipi_edit', $tipo["id"]) ?>">
                                                        <i class="bi bi-pencil"></i>
                                                        Modifica
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li class="">
                                                    <a class="dropdown-item text-danger" href="<?= url_to('tipi_delete', $tipo["id"]) ?>">
                                                        <i class="bi bi-trash"></i>
                                                        Elimina
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nessun elemento trovato nel database.
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // inizializza la DataTable 
        const table = $('#tipiTable').DataTable($.extend(true, {}, datatableDefaults, {
            order: []
        }));

        
        document.querySelectorAll('.clickable').forEach(function(input) {
            input.addEventListener('dblclick', function() {
                const ID = this.getAttribute('data-id');
                console.log('Doppio click sul tipo ID: ' + ID);
                window.location.href = "<?= base_url('tipi/edit/') ?>" + ID;
            });
        });


    });
</script>
<?= $this->endSection() ?>