<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Utenti</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-people"></i> Gestione Utenti</h5>
    <a href="<?= url_to('utenti_crea') ?>" class="btn btn-outline-secondary btn-sm" title="Aggiungi nuovo utente">
        <i class="bi bi-person-plus"></i> Nuovo Utente
    </a>
</div>

<?php if (!empty($usersList) && is_array($usersList)): ?>
    <table class="table table-bordered table-striped table-hover align-middle datatable" id="primaryTable">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Gruppi</th>
                <th>Permessi</th>
                <th>Creato il</th>
                <th class="notexport">Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usersList as $utente): ?>
                <tr class="data-row" data-id="<?= esc($utente->id) ?>">
                    <td><?= esc($utente->id) ?></td>
                    <td><?= esc($utente->username) ?></td>
                    <td><?= esc($utente->email) ?></td>
                    <td>
                        <?php foreach ($utente->getGroups() as $group): ?>
                            <?php if ($group === 'pending') $person_check = true; ?>
                            <span class="badge bg-secondary"><?= esc($group) ?></span>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($utente->getPermissions() as $permission): ?>
                            <span class="badge bg-info"><?= esc($permission) ?></span>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?= $utente->created_at ? $utente->created_at->format('d/m/Y H:i') : '-' ?>
                    </td>
                    <td>
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="<?= url_to('utenti_scheda', $utente->id) ?>">
                                    <i class="bi bi-person-vcard"></i> Scheda Utente
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= url_to('utenti_modifica', $utente->id) ?>">
                                    <i class="bi bi-pencil"></i> Modifica
                                </a>
                            </li>
                            <?php if (isset($person_check)): ?>
                                <li>
                                    <a class="dropdown-item text-success"
                                        href="<?= site_url('utenti/approva/' . $utente->id) ?>"
                                        onclick="return confirm('Approvare questo utente?')">
                                        <i class="bi bi-person-check"></i> Approva
                                    </a>
                                </li>
                                <?php unset($person_check); ?>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger"
                                    href="<?= url_to('utenti_elimina', $utente->id) ?>"
                                    onclick="return confirm('Eliminare questo utente?')">
                                    <i class="bi bi-trash"></i> Elimina
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nessun utente trovato.
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#primaryTable').DataTable($.extend(true, {}, datatableDefaults, {
            order: [],
        }));
    });
</script>
<?= $this->endSection() ?>
