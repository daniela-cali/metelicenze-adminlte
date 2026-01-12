<?php $this->extend('layouts/main') ?>
<?php $this->section('content') ?>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key"></i> Elenco Licenze</h5>

            </a>
        </div>
        <div class="card-body">
            <div class="container">


                <div class="row"> <!-- row filtri -->
                    <div class="col">
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
                    <hr>
                    <div class="col">
                        <div class="d-flex justify-content-end align-items-center gap-3" id="statoLicenze">
                            <div class="form-check">
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statoLicenze" value="Tutte"
                                         checked
                                        id="Tutte">
                                    <label class="form-check-label" for="Tutte">Tutte</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statoLicenze" value="Aggiornato"
                                        id="aggiornate">
                                    <label class="form-check-label" for="aggiornate">Aggiornate</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statoLicenze" value="Da aggiornare"
                                        id="da_aggiornare">
                                    <label class="form-check-label" for="da_aggiornare">Da aggiornare</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div> <!-- row filtri -->
                <?php if (!empty($licenze)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle" id="licenzeTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Licenza</th>
                                    <th>Codice</th>
                                    <th>Tipo</th>
                                    <th>Cliente</th>
                                    <th>Data Ult. Agg.</th>
                                    <th>Versione attuale</th>
                                    <th>Aggiornato</th>
                                    <th class="notexport">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($licenze as $licenza): ?>
                                    <?php $trClass = $licenza->stato == 0 ? 'class="table-danger"' : ''; ?>
                                    <tr <?= $trClass ?> class = "licenza-row" data-id="<?= esc($licenza->id) ?>" style="cursor:pointer;">
                                        <td><?= esc($licenza->id) ?></td>
                                        <td><?= esc($licenza->codice) ?></td>
                                        <td><?= esc($licenza->tipo) ?></td>
                                        <td><?= esc($licenza->clienteNome) ?></td>
                                        <td><?= esc($licenza->ultimoAggiornamento) ?></td>
                                        <td><?= esc($licenza->versioneUltimoAggiornamento) ?></td>
                                        <td>
                                            <?php if ($licenza->ultimaVersione): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check"></i>
                                                    Aggiornato
                                                </span>
                                            <?php elseif ($licenza->stato == 0): ?>
                                                <span class="badge bg-danger text-white">
                                                    <i class="bi bi-x-circle"></i>
                                                    Scaduta
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    Da aggiornare
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" id="azione" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-list"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= site_url('aggiornamenti/crea/' . $licenza->id . '/' . $licenza->tipo) ?>">
                                                            <i class="bi bi-clock-history"></i>
                                                            Aggiornamento
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= site_url('licenze/visualizza/' . $licenza->id) ?>">
                                                            <i class="bi bi-eye"></i>
                                                            Visualizza
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= site_url('licenze/modifica/' . $licenza->id) ?>">
                                                            <i class="bi bi-pencil"></i>
                                                            Modifica
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

                                                    <li class="">
                                                        <a class="dropdown-item text-danger" href="<?= site_url('licenze/elimina/' . $licenza->id) ?>">
                                                            <i class="bi bi-trash"></i>
                                                            Elimina
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Nessuna licenza trovata nel database.
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
            const table = $('#licenzeTable').DataTable($.extend(true, {}, datatableDefaults, {
                order: []
            }));

            // filtro custom: tipi + stato licenze
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'licenzeTable') return true;

                // ---- filtro TIPI (esempio: colonna 2)
                const selectedTipi = $('input[name="tipi"]:checked').map(function() {
                    return $(this).val();
                }).get();

                let passTipi = true;
                if (selectedTipi.length > 0) {
                    const tipiText = String(data[2] ?? '')
                        .replace(/<[^>]*>/g, '') // toglie eventuale HTML
                        .replace(/\s+/g, ' ') // compatta whitespace
                        .trim();

                    passTipi = selectedTipi.some(t => tipiText.includes(t));
                }

                // ---- filtro STATO (colonna 6)
                const selectedStati = $('input[name="statoLicenze"]:checked').map(function() {
                    return $(this).val(); // es: "Aggiornato" / "Da aggiornare" / "Tutte"
                }).get();

                let passStato = true;
                if (selectedStati.length > 0) {
                    //console.log('Filtrando per stati: ' + selectedStati);
                    const statoText = String(data[6] ?? '')
                        .replace(/<[^>]*>/g, '')
                        .replace(/\s+/g, ' ')
                        .trim();
                    if (selectedStati.includes('Tutte')) { // nessun filtro e passo true
                        passStato = true;
                    } else {
                        passStato = selectedStati.includes(statoText); //filtro attivo e passo il tipo letto
                    }
                }
                //console.log('Filtro tipi: ' + passTipi + ', filtro stato: ' + passStato);
                return passTipi && passStato;
            });
            document.querySelectorAll('.licenza-row').forEach(function(input) {
                input.addEventListener('dblclick', function() {
                    const licenzaId = this.getAttribute('data-id');
                   console.log('Doppio click sulla licenza ID: ' + licenzaId);
                    window.location.href = '/licenze/modifica/' + licenzaId;
                });
            });

            // quando cambia un filtro, ridisegna la tabella 
            document.querySelectorAll('input[name="statoLicenze"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    //console.log('Ridisegno tabella per filtro statoLicenze valore selezionato: ' + this.value);
                    table.draw();
                });
            });

            document.querySelectorAll('input[name="tipi"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    //console.log('Ridisegno tabella per filtro tipi valore selezionato: ' + this.value);
                    table.draw();
                });
            });


        });
    </script>
    <?= $this->endSection() ?>