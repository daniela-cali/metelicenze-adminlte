<?php $this->extend('layouts/main') ?>

<?php $this->section('breadcrumb'); ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('fornitori_index') ?>">Fornitori</a></li>
    <li class="breadcrumb-item active">Tipi Licenze</li>
</ol>
<?php $this->endSection(); ?>

<?php $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-tags-fill"></i> Elenco Tipi Licenze</h5>
    <a href="<?= url_to('tipi_crea') ?>" class="btn btn-outline-secondary btn-sm" title="Aggiungi nuovo tipo di licenza">
        <i class="bi bi-plus-circle"></i> Nuovo tipo di licenza
    </a>
</div>

<?php if (!empty($tipiLicenze)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle" id="tipiTable">
            <thead class="table-secondary">
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
                        data-id="<?= esc($tipo["id"]) ?>"
                        <?= audit_tooltip($tipo) ?>>
                        <td><?= esc($tipo["id"]) ?></td>
                        <td><?= esc($tipo["nome"]) ?></td>
                        <td><?= esc($tipo["categoria_label"]) ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-list"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="<?= url_to('tipi_scheda', $tipo["id"]) ?>">
                                            <i class="bi bi-eye"></i> Visualizza
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= url_to('tipi_modifica', $tipo["id"]) ?>">
                                            <i class="bi bi-pencil"></i> Modifica
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?= url_to('tipi_elimina', $tipo["id"]) ?>">
                                            <i class="bi bi-trash"></i> Elimina
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

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const table = $('#tipiTable').DataTable($.extend(true, {}, datatableDefaults, {
            order: []
        }));

        document.querySelectorAll('.clickable').forEach(function(input) {
            input.addEventListener('dblclick', function() {
                const ID = this.getAttribute('data-id');
                window.location.href = "<?= base_url('tipi/edit/') ?>" + ID;
            });
        });
    });
</script>
<?= $this->endSection() ?>
