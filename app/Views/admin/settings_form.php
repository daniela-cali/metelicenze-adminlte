
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h2>Impostazioni del sito</h2>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/settings/save') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="siteName" class="form-label">Nome del sito</label>
            <input type="text" name="siteName" class="form-control" id="siteName" value="">
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="maintenanceMode" id="maintenanceMode" >
            <label class="form-check-label" for="maintenanceMode">
                Modalità manutenzione
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Salva</button>
    </form>
</div>

<?= $this->endSection() ?>
