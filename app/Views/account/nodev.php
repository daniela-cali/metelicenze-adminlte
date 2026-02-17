<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Account non abilitato allo sviluppo</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Il tuo account non è abilitato allo sviluppo</h4>
                <p>Non hai i permessi necessari per accedere alle funzionalità di sviluppo. Contatta un amministratore per richiedere l'accesso.</p>
            </div>  
            <p class="text-muted">Se ritieni che ci sia un errore o hai bisogno di assistenza, contatta l'amministratore del sistema.</p>
        </div>
    </div>
</div>  
<?= $this->endSection() ?>

