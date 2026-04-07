<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('utenti_index') ?>">Utenti</a></li>
    <li class="breadcrumb-item active"><?= esc($title) ?></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$backTo = $backTo ?? site_url('utenti');
?>
<div class="card shadow-sm">
    <div class="card-header card-header-muted d-flex align-items-center">
        <h5 class="mb-0"><i class="bi bi-person-gear"></i> <?= esc($title) ?></h5>
        <a href="<?= esc($backTo) ?>" class="btn btn-light btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Indietro
        </a>
    </div>
    <div class="card-body">
        <form action="<?= $action ?>" method="post">
            <?= csrf_field() ?>
            <fieldset class="border p-3 mb-4">
                <legend class="float-none w-auto px-3 h5">Credenziali utente</legend>
                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text"
                        class="form-control"
                        name="username"
                        value="<?= esc($user->username ?? '') ?>"
                        <?= $mode === 'view' ? 'readonly' : '' ?>
                        required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email"
                        class="form-control"
                        name="email"
                        value="<?= esc($user->email ?? '') ?>"
                        <?= $mode === 'view' ? 'readonly' : '' ?>
                        required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('utenti/changePassword') ?>" class="btn btn-outline-secondary">Cambia Password</a>
                </div>
            </fieldset>

            <!-- Gruppi -->
            <fieldset class="border p-3 mb-4">
                <legend class="float-none w-auto px-3 h5">Gruppi di appartenenza</legend>
                <div class="mb-4">
                    <div class="row">
                        <?php foreach ($allGroups as $group => $value): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    <?= $mode === 'view' ? 'disabled="disabled"' : '' ?>
                                    name="groups[]"
                                    value="<?= $group ?>"
                                    id="group_<?= esc($value["title"]) ?>"
                                    <?= isset($user->groups) && in_array($group, $user->groups) ? 'checked' : '' ?>
                                <label class="form-check-label" for="group_<?= esc($value["title"]) ?>">
                                    <?= esc($value["title"]) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </fieldset>

            <!-- Pulsanti -->
            <?php if ($mode !== 'view'): ?>
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('utenti') ?>" class="btn btn-outline-secondary">Modifica Password</a>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            <?php else: ?>
                <a href="<?= site_url('utenti') ?>" class="btn btn-outline-secondary">Torna indietro</a>
            <?php endif; ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
