<?php $this->extend('layouts/main'); ?>
<?php $this->section('content'); ?>

<div class="container">
    <div class="main-container">
        <!-- Header -->
        <div class="header-section text-center">
            <h1 class="display-4 mb-2">
                <i class="bi bi-database"></i> Database Information
            </h1>
            <p class="lead mb-0">MeTe Licenze - Sistema di Monitoraggio Database</p>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Status Badge -->
            <div class="status-connection">
                <i class="bi bi-check-circle-fill"></i>
                Connessione Database Attiva
            </div>

            <!-- Info Cards Row -->
            <div class="row g-4 mb-4">
                <!-- Database Info Card -->
                <div class="col">
                    <div class="card h-100 info-card">
                        <div class="card-header">
                            <i class="bi bi-server"></i> Database
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-5"><strong>Database:</strong></div>
                                <div class="col-7">
                                    <span class="table-name"><?= esc($dbInfo->db_name) ?></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Connection group:</strong></div>
                                <div class="col-7">
                                    <span class="table-name"><?= esc($database) ?></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Encoding:</strong></div>
                                <div class="col-7">
                                    <span class="badge bg-primary"><?= esc($dbInfo->encoding) ?></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5"><strong>Collation:</strong></div>
                                <div class="col-7">
                                    <span class="table-name"><?= esc($dbInfo->collation) ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5"><strong>CType:</strong></div>
                                <div class="col-7">
                                    <span class="table-name"><?= esc($dbInfo->ctype) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schema Info Card -->
                <div class="col">
                    <div class="card h-100 schema-card">
                        <div class="card-header">
                            <i class="bi bi-diagram-3"></i> Schema
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6"><strong>Schema Attivo:</strong></div>
                                <div class="col-6">
                                    <span class="badge bg-danger fs-6"><?= esc($schema) ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Tabelle:</strong></div>
                                <div class="col-6">
                                    <span class="badge bg-success fs-6"><?= count($tables) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <!-- Tables List Card -->
                <div class="col">
                    <div class="card h-300 tables-card overflow-auto">
                        <div class="card-header">
                            <i class="bi bi-table"></i> Tabelle Disponibili
                            <?php if (!empty($tables)): ?>
                                <span class="badge bg-light text-dark ms-2"><?= count($tables) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($tables)): ?>
                                <div class="alert alert-warning m-3">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Nessuna tabella trovata nello schema <strong><?= esc($schema) ?></strong>
                                </div>
                            <?php else: ?>
                                <div class="tables-list-container p-3">
                                    <?php foreach ($tables as $table): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><i class="bi bi-table me-2"></i><?= esc($table->tablename) ?></span>
                                            
                                                <a href="<?= site_url('database/fields/' . $database . '/' . urlencode($table->tablename)) ?>">

                                                <span class="table-name">
                                                    <?= esc($schema ? $schema . '.' : '') ?><?= esc($table->tablename) ?>
                                                </span>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Back Button -->
            <div class="mt-4">
                <a href="/database" class="btn btn-custom">
                    <i class="bi bi-arrow-left"></i> Torna al Test Database
                </a>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>