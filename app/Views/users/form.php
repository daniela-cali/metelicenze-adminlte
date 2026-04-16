<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('users_index') ?>">Utenti</a></li>
    <li class="breadcrumb-item active"><?= esc($title) ?></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Fallback nel caso in cui $backTo non venga passato esplicitamente
$backTo = $backTo ?? url_to('users_index');
?>
<div class="card shadow-sm">
    <div class="card-header card-header-muted d-flex align-items-center">
        <h5 class="mb-0"><i class="bi bi-person-gear"></i> <?= esc($title) ?></h5>
        <a href="<?= esc($backTo) ?>" class="btn btn-light btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Indietro
        </a>
    </div>
    <div class="card-body">
        <form action="<?= $form['action'] ?>" method="<?= $form['method'] ?>" data-mode="<?= $mode ?>">
            <?= csrf_field() ?>
            <!-- Spoof del metodo HTTP (es. PUT per la modifica) -->
            <?php if ($form['spoof']): ?>
                <input type="hidden" name="_method" value="<?= $form['spoof'] ?>">
            <?php endif; ?>
            <!-- Trasporta l'URL di provenienza attraverso il submit del form -->
            <input type="hidden" name="backTo" value="<?= esc($backTo) ?>">

            <fieldset class="border p-3 mb-4">
                <legend class="float-none w-auto px-3 h5">Credenziali utente</legend>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text"
                        class="form-control"
                        name="username"
                        value="<?= esc($user->username ?? '') ?>"
                        <?= $form['readonly'] ? 'readonly' : '' ?>
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email"
                        class="form-control"
                        name="email"
                        value="<?= esc($user->email ?? '') ?>"
                        <?= $form['readonly'] ? 'readonly' : '' ?>
                        required>
                </div>

                <?php if ($mode === 'create'): ?>
                    <!-- Password: visibile solo in creazione, la modifica avviene tramite changePassword -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password"
                            class="form-control"
                            name="password"
                            value=""
                            required>
                    </div>
                <?php endif; ?>

                <?php if ($mode !== 'view'): ?>
                    <div class="d-flex">
                        <a href="<?= site_url('utenti/changePassword') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-key"></i> Cambia Password
                        </a>
                    </div>
                <?php endif; ?>
            </fieldset>

            <!-- Gruppi di appartenenza -->
            <fieldset class="border p-3 mb-4">
                <legend class="float-none w-auto px-3 h5">Gruppi di appartenenza</legend>
                <div class="row">
                    <?php foreach ($allGroups as $group => $value): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                <?= $form['readonly'] ? 'disabled="disabled"' : '' ?>
                                name="groups[]"
                                value="<?= $group ?>"
                                id="group_<?= esc($value['title']) ?>"
                                <?= isset($user) && $user !== null && in_array($group, $user->getGroups()) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="group_<?= esc($value['title']) ?>">
                                <?= esc($value['title']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

                        <!-- Permessi -->
            <fieldset class="border p-3 mb-4">
                <legend class="float-none w-auto px-3 h5">Permessi</legend>
                <div class="row">
                    <?php foreach ($allPermissions as $permission => $value): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                <?= $form['readonly'] ? 'disabled="disabled"' : '' ?>
                                name="permissions[]"
                                value="<?= $permission ?>"
                                id="permission_<?= esc($value) ?>"
                                <?= isset($user) && $user !== null && in_array($permission, $user->getPermissions()) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="permission_<?= esc($value)?>">
                                <?= "<b><i>(" . esc($permission) . ")</i></b>: " . esc($value) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>            

            <!-- Pulsanti azione -->
            <?php if ($mode !== 'view'): ?>
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> <?= esc($form['submitText']) ?>
                    </button>
                    <a href="<?= esc($backTo) ?>" class="btn btn-secondary">Annulla</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
