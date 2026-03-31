<?php $this->extend('layouts/main') ?>
<?php $this->section('content') ?>

<div class="container my-5">
    <div class="main-container p-4">

        <!-- Header -->
        <div class="header-section mb-4">
            <h1 class="h3 mb-2">
                <i class="bi bi-people"></i> Elenco Utenti
            </h1>
            <p class="text-muted mb-0">Gestione utenti MeTe Licenze</p>
        </div>

        <!-- Barra azioni -->
        <div class="d-flex justify-content-between mb-3">
            <div>
                <a href="<?= base_url('utenti/crea') ?>" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Nuovo Utente
                </a>
            </div>
        </div>

        <!-- Tabella -->
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-striped align-middle" id="utentiTable">
                <thead class="table-secondary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Ruoli</th>
                        <th scope="col">Creato il</th>
                        <th scope="col" class="text-end notexport">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($usersList) && is_array($usersList)): ?>
                        <?php foreach ($usersList as $utente): ?>
                            <tr>
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
                                    <?= $utente->created_at ? $utente->created_at->format('d/m/Y H:i') : '-' ?>
                                </td>
                                <td class="text-end">
                                    <?php if (isset($person_check)) : ?>
                                        <a href="<?= base_url('utenti/approva/' . $utente->id) ?>"
                                            class="btn btn-sm btn-outline-success"
                                            title="Approva"
                                            onclick="return confirm('Approvare questo utente?')">
                                            <i class="bi bi-person-check"></i>
                                        </a>
                                    <?php unset($person_check);
                                    endif; ?>
                                    <a href="<?= base_url('utenti/visualizza/' . $utente->id) ?>"
                                        class="btn btn-sm btn-outline-info" title="Dettaglio">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('utenti/modifica/' . $utente->id) ?>"
                                        class="btn btn-sm btn-outline-secondary" title="Modifica">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="<?= base_url('utenti/elimina/' . $utente->id) ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Elimina"
                                        onclick="return confirm('Eliminare questo utente?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-exclamation-circle"></i> Nessun utente trovato.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>

</script>
<?php $this->endSection() ?>