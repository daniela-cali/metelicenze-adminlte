<?= $this->extend('layouts/main') ?>



<?= $this->section('content') ?>


<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-box-arrow-down"></i> Pagina in costruzione</h5>

</div>

    <div class="mb-3">
        <h1 class="display-5 mb-2"><?php echo $title; ?></h1>
        <img src="<?= base_url('assets/images/under-construction.png') ?>" alt="Pagina in costruzione" class="img-fluid mb-4" style="max-width: 100%;">
    </div>


</div>
<?= $this->endSection() ?>