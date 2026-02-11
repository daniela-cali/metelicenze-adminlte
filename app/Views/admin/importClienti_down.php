<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <div class="header-section text-center mb-5">
            <h1 class="display-5 mb-2">Importazione Clienti Non Disponibile </h1>
        </div>
<div class="card">
  <div class="card-header">
    Attenzione
  </div>
  <div class="card-body">
    <h5 class="card-title">
        <i class="bi bi-exclamation-triangle"></i>
        Importazione Clienti Non Disponibile</h5>
    <p class="card-text">Il database esterno non è accessibile. Verificare la connessione ad esso.</p>
    <a href="<?= base_url('/') ?>" class="btn btn-light btn-outline-secondary btn-sm">Torna alla home</a>
  </div>
</div>
</div>

<?= $this->endSection() ?>
