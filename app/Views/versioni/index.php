<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people"></i> Elenco Versioni</h5>
            <a href="/versioni/crea" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-plus-circle"></i> Aggiungi versione
            </a>
        </div>
        <div class="card-body">
           <div class="container"><!-- FILTRI -->
                <div class="row">  
                    <div class="col"> <!--TIPO-->
                        <div class="d-flex justify-content-end align-items-center gap-3" id="tipi">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tipi" value="Sigla"
                                    id="tipoSigla">
                                <label class="form-check-label" for="tipoSigla">Sigla</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tipi" value="VarHub"
                                    id="tipoVarHub">
                                <label class="form-check-label" for="tipoVarHub">VarHub</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="tipi" value="SKNT" id="tipoSKNT">
                                <label class="form-check-label" for="tipoSKNT">SKNT</label>
                            </div>
                        </div>
                    </div>
                </div> <!-- END FILTRI -->
                <hr>
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
                                <tr class="clickable" data-id="<?= esc($versione["id"]) ?>">
                                    <td><?= esc($versione["id"]) ?></td>
                                    <td><?= esc($versione["tipo"]) ?></td>
                                    <td><?= esc($versione["codice"]) ?></td>
                                    <td><?= esc($versione["release"]) ?></td>
                                    <td><?= date('d/m/Y', strtotime($versione["dt_rilascio"])) ?></td>
                                    <td>
                                        <?php if ($versione["ultima"]): ?>
                                            <span class="badge bg-success">Ultima</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><i>Superata</i></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/versioni/visualizza/<?= $versione["id"] ?>" class="btn btn-sm btn-outline-primary" title="Scheda versione">
                                            <i class="bi bi-person-vcard"></i>
                                        </a>
                                        <a href="/versioni/modifica/<?= $versione["id"] ?>" class="btn btn-sm btn-outline-secondary" title="Modifica versione">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/versioni/elimina/<?= $versione["id"] ?>" class="btn btn-sm btn-outline-danger" title="Elimina versione" onclick="return confirm('Eliminare la versione?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>

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
   document.addEventListener("DOMContentLoaded", function() {
            // inizializza la DataTable 
            const table = $('#versioniTable').DataTable($.extend(true, {}, datatableDefaults, {
                order: []
            }));

            // filtro custom: tipi + stato licenze
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'versioniTable') return true;

                // ---- filtro TIPI (esempio: colonna 1)
                const selectedTipi = $('input[name="tipi"]:checked').map(function() {
                    return $(this).val();
                }).get();

                let passTipi = true;
                if (selectedTipi.length > 0) {
                    const tipiText = String(data[1] ?? '')
                        .replace(/<[^>]*>/g, '') // toglie eventuale HTML
                        .replace(/\s+/g, ' ') // compatta whitespace
                        .trim();

                    passTipi = selectedTipi.some(t => tipiText.includes(t));
                }

                
                return passTipi;
            });
            document.querySelectorAll('.clickable').forEach(function(input) {
                /*input.addEventListener('click', function() {
                    const ID = this.getAttribute('data-id');
                   console.log('Click singolo ID: ' + ID);
                    window.location.href = '/versioni/visualizza/' + ID;
                });*/
                input.addEventListener('dblclick', function() {
                    const ID = this.getAttribute('data-id');
                   console.log('Doppio click ID: ' + ID);
                    window.location.href = '/versioni/modifica/' + ID;
                });
            });

            // quando cambia un filtro, ridisegna la tabella 

            document.querySelectorAll('input[name="tipi"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    //console.log('Ridisegno tabella per filtro tipi valore selezionato: ' + this.value);
                    table.draw();
                });
            });


        });

</script>
<?= $this->endSection() ?>