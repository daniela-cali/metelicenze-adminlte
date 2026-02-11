<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h1><?= esc($title) ?></h1>
<p>Qui puoi importare i clienti nel database interno.</p>

<form action="<?= site_url('admin/import_clienti') ?>" method="post" class="mb-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-upload"></i> Importa Clienti
    </button>
</form>
<table id="importTable" class="table table-striped datatable">
    <thead>
        <tr>
            <th>ID Ext.</th>
            <th>ID</th>
            <th>Codice Cliente</th>
            <th>Ragione sociale</th>
            <th>PIva</th>            
            <th>Indirizzo</th>
            <th>Città</th>
            <th>Telefono</th>
            <th class= "notexport">Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($clienti) && is_array($clienti)): ?>
            <?php foreach ($clienti as $cliente): ?>
                <tr>
                    <td><?= esc($cliente["id_external"]) ?></td>
                    <td>
                        <?php if ($cliente["id"]): ?>
                            <span class="badge bg-success"><?= esc($cliente["id"]) ?></span>
                        <?php else: ?>
                            <span class="badge bg-primary">Nuovo</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($cliente["codice"]) ?></td>
                    <td><?= esc($cliente["nome"]) ?></td>
                    <td><?= esc($cliente["piva"]) ?></td>
                    <td><?= esc($cliente["indirizzo"]) ?></td>
                    <td><?= esc($cliente["provincia"]) ?></td>
                    <td><?= esc($cliente["telefono"]) ?></td>
                    <td><?= esc($cliente["email"]) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Nessun cliente disponibile per l'importazione.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('.datatable')) {
        $('.datatable').DataTable(datatableDefaults);
    }
});
</script>
<?= $this->endSection() ?>
