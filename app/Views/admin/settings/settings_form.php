<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Impostazioni</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header card-header-muted">
        <h5 class="mb-0"><i class="bi bi-gear"></i> Impostazioni sito</h5>
    </div>
    <div class="card-body">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('admin/settings/save') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nome del sito</label>
                <input type="text" name="siteName" class="form-control"
                       value="<?= esc($settings['siteName']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tema</label>
                <input type="text" name="siteTheme" class="form-control"
                       value="<?= esc($settings['siteTheme']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email amministratore</label>
                <input type="email" name="adminEmail" class="form-control"
                       value="<?= esc($settings['adminEmail']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Logo</label>
                <?php if (!empty($settings['logoPath'])): ?>
                    <div class="mb-2">
                        <img src="<?= esc($settings['logoPath']) ?>" alt="Logo attuale" style="max-height: 60px;">
                        <small class="text-muted ms-2"><?= esc($settings['logoPath']) ?></small>
                    </div>
                <?php endif; ?>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <div class="form-text">Lascia vuoto per mantenere il logo attuale. Formati accettati: PNG, JPG, GIF, WEBP, SVG.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">URL del sito</label>
                <input type="url" name="siteURL" class="form-control"
                       value="<?= esc($settings['siteURL']) ?>">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="enableMultiDB"
                       id="enableMultiDB" value="1"
                       <?= $settings['enableMultiDB'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="enableMultiDB">
                    Abilita secondo database
                </label>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Salva impostazioni
            </button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
