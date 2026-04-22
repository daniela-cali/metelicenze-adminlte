<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container my-5">
  <div class="header-section text-center mb-5">
    <h1 class="display-5 mb-2">Importazione Clienti Non Disponibile </h1>
  </div>

  <div class="card">
    <div class="card-header bg-warning text-dark">
      Attenzione
    </div>
    <div class="card-body">
      <p class="fw-semibold mb-1">
        <i class="bi bi-exclamation-triangle"></i>
        Importazione Clienti Non Disponibile
      </p>
      <p class="text-muted mb-3">Il database esterno non è accessibile. Verificare la connessione ad esso.</p>

      <a href="<?= url_to('import_index') ?>" class="btn btn-light btn-outline-secondary btn-sm">Torna all'importazione</a>
    </div>
  </div>
</div>

<?= $this->endSection() ?>