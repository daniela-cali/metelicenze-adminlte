<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people"></i> Elenco Versioni</h5>
            <a href="/versioni/crea" class="btn btn-secondary btn-sm">
                <i class="bi bi-plus-circle"></i> Aggiungi versione
            </a>
        </div>
        <div class="card-body">

            <?php if (!empty($versioni)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="versioniTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Codice</th>
                                <th>Release</th>
                                <th>Data Rilascio</th>
                                <th>Stato</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($versioni as $versione): ?>
                                <tr>
                                    <td><?= esc($versione->id) ?></td>
                                    <td><?= esc($versione->tipo) ?></td>
                                    <td><?= esc($versione->codice) ?></td>
                                    <td><?= esc($versione->release) ?></td>
                                    <td><?= date('d/m/Y', strtotime($versione->dt_rilascio)) ?></td>
                                    <td>
                                        <?php if ($versione->ultima): ?>
                                            <span class="badge bg-success">Ultima</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i>Superata</i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/versioni/visualizza/<?= $versione->id ?>" class="btn btn-sm btn-outline-primary" title="Scheda versione">
                                            <i class="bi bi-person-vcard"></i>
                                        </a>
                                        <a href="/versioni/modifica/<?= $versione->id ?>" class="btn btn-sm btn-outline-secondary" title="Modifica versione">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/versioni/elimina/<?= $versione->id ?>" class="btn btn-sm btn-outline-danger" title="Elimina versione" onclick="return confirm('Eliminare la versione?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <td></td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nessuna versione trovata nel database.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        var table = new DataTable('#versioniTable', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.2/i18n/it-IT.json',
            },
            responsive: true,
            order: [],
            columnDefs: [
                {
                    targets: 7,
                    orderable: true,
                    searchable: true
                }       
            ]
            ,
            paging: true,
            lengthChange: false,
            info: true, 
            searching: true,
            autoWidth: false,
            
        });
    });

</script>
<?= $this->endSection() ?>
