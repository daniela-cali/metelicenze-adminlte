<?php
$this->extend('layouts/main'); 

$this->section('content'); ?>

<div class="container my-5">
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">ID non valido!</h4>
        <p>Il fornitore con ID specificato non esiste o non è accessibile.</p>
        <hr>
        <p class="mb-0">Controlla l'ID fornito e riprova.</p>
    </div>
</div>
<?= $this->endSection() ?>