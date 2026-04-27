<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('import_index') ?>">Import</a></li>
    <li class="breadcrumb-item active">Associazione campi</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-link"></i> Pagina di associazione campi</h5>

</div>

<div class="container">
    <div class="table-responsive">
        <form action="<?= url_to('import_store_mapping') ?>" method = "post">
        <?= csrf_field() ?>
        <input type= " hidden" name="tabella" value="<?= esc($tabella) ?>">
            <table class="table table-bordered ">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Campo di Destinazione</th>
                        <th>Campo prelevato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campiInterni as $key => $campo): ?>
                        <tr>
                            <td><?= esc($key + 1) ?>.</td>
                            <td><?= esc($campo["campo_dest"]) ?></td>
                            <td>
                                <input type="text"
                                    name="mapping[<?= esc($campo["campo_dest"]) ?>]"
                                    list="campi-<?= esc($campo["campo_dest"]) ?>"
                                    class="form-control form-control-sm">
                                <datalist id="campi-<?= esc($campo["campo_dest"]) ?>">
                                    <?php foreach ($campiEsterni as $esterno): ?>
                                        <option value="<?= esc($esterno) ?>">
                                        <?php endforeach; ?>
                                </datalist>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-secondary mt-3">Salva mappatura</button>
        </form>

    </div>
</div>


<?= $this->endSection() ?>