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
        <div id="start" class="p-4">

            <h2>Campi trovati</h2>
            <p>Di seguito l'elenco dei campi trovati nella tabella <strong><?= esc($table_name) ?></strong>:</p>
            <!-- Back Button -->
            <div class="d-flex flex-column align-items-end gap-2 my-4">
                <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Torna alla pagina precedente
                </button>
                <a href="<?= url_to('databaseinfo_connectiontest') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Torna al Test Database
                </a>
            </div>

            <table id="primary" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Chiave</th>
                        <th>Campo</th>
                        <th>Tipo</th>
                        <th>Null</th>
                        <th>Default</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($fields)): ?>
                        <?php foreach ($fields as $field): ?>
                            <tr>
                                <td><?= (strpos($field["column_name"], '_id_pk') !== false || $field["column_name"] === 'id') ?
                                        '<span class="badge bg-warning">Chiave primaria</span>' :
                                        '<span class="badge bg-secondary">Campo</span>' ?></td>
                                <td><?= esc($field["column_name"]) ?></td>
                                <td><?= esc($field["data_type"]) ?></td>
                                <td><?= esc($field["is_nullable"]) ?></td>
                                <td><?= $field["column_default"] !== null ? esc($field["column_default"]) : '<i>null</i>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
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
                echo '<pre>protected $allowedFields = [';
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
        <div class="mt-4 p-4">
            <a href="#start" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-up"></i> Torna in cima
            </a>
        </div>
    </div>

    <?php $this->endSection() ?>