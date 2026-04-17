<?php $this->extend('layouts/main'); ?>
<?php $this->section('content') ?>
<div class="container">
    <div class="main-container">
        <!-- Header -->
        <div class="header-section text-center">
            <h1 class="display-4 mb-2">
                <i class="bi bi-check-circle"></i> Elenco Campi Tabella <?= esc($table_name) ?>
            </h1>
            <p class="lead mb-0">MeTe Licenze elenco campi</p>
        </div>
        <!-- Content -->
        <div class="p-4">
            <h2>Campi trovati</h2>
            <p>Di seguito l'elenco dei campi trovati nella tabella <strong><?= esc($table_name) ?></strong>:</p>

            <?php if (!empty($fields)): ?>
                <ul class="list-group">
                    <?php foreach ($fields as $field): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= esc($field["column_name"]) ?>
                            <?php if (strpos($field["column_name"], '_id_pk') !== false || $field["column_name"] === 'id'): ?>
                                <span class="badge bg-warning">Chiave primaria</span>


                            <?php else: ?>
                                <span class="badge bg-secondary">Campo</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (empty($fields)): ?>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i> Nessun campo trovato nella tabella <strong><?= esc($table_name) ?></strong>
                </div>
            <?php endif; ?>
        </div>
        <div class="p-4">
            <?php if (!empty($allowed_fields)): ?>
                <h2>Genera $allowedFields</h2>
                <p>Di seguito il codice per generare l'array <code>$allowedFields</code>:</p>
                <?php
                echo '<pre> protected $allowedFields = ['; 
                echo "\n";

                foreach ($allowed_fields as $field) {
                    echo "      '" . esc(trim($field)) . "', \n ";
                }
                echo "];</pre>";
                ?>

            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Nessun campo valido trovato per generare $allowedFields.
                </div>
            <?php endif; ?>
        </div>
        <!-- Back Button -->
        <div class="mt-2">
            <button type="button" onclick="history.back()" class="btn btn-custom">
                <i class="bi bi-arrow-left"></i> Torna alla pagina precedente
            </button>
        </div>

        <div class="mt-2">
            <a href="/database" class="btn btn-custom">
                <i class="bi bi-arrow-left"></i> Torna al Test Database
            </a>
        </div>
    </div>
</div>

<?php $this->endSection() ?>