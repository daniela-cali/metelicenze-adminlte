<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="container d-flex justify-content-center p-5">
        <div class="card col-12 col-md-5 shadow-sm">
            <div class="card-body">
                <div class="card-header bg-dark text-white mb-4">
                    <img src="<?=  config('SiteConfig')->logoPath ?>" alt="MeTe Licenze" class="img-fluid" style="max-height: 60px;">
                    <h5 class="card-title mt-2 float-end"> <?=  config('SiteConfig')->siteName ?> Login</h5>
                </div>

                <!-- Form di login: estratto in partial condiviso con il modal della home -->
                <?= $this->include('Shield/_login_form') ?>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
