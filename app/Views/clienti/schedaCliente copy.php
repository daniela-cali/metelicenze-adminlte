<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4"> <!-- Limita la larghezza -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary  d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Dettaglio Cliente</h5>

            <a href="/clienti" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Torna all’elenco clienti
            </a>
        </div>

        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="clienteTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="anagrafica-tab" data-bs-toggle="tab" data-bs-target="#anagrafica" type="button" role="tab" aria-controls="anagrafica" aria-selected="true">
                        <i class="bi bi-file-person"></i> Anagrafica
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="licenze-tab" data-bs-toggle="tab" data-bs-target="#licenze" type="button" role="tab" aria-controls="licenze" aria-selected="false">
                        <i class="bi bi-key"></i> Licenze
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="aggiornamenti-tab" data-bs-toggle="tab" data-bs-target="#aggiornamenti" type="button" role="tab" aria-controls="aggiornamenti" aria-selected="false">
                        <i class="bi bi-clock-history"></i> Aggiornamenti
                    </button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-3" id="clienteTabContent">
                <!-- ANAGRAFICA -->
                <div class="tab-pane fade show active" id="anagrafica" role="tabpanel" aria-labelledby="anagrafica-tab">
                    <dl class="row">
                        <dt class="col-sm-3">Codice Cliente</dt>
                        <dd class="col-sm-9"><?= esc($cliente->codice) ?></dd>

                        <dt class="col-sm-3">Nome</dt>
                        <dd class="col-sm-9"><?= esc($cliente->nome) ?></dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9"><?= esc($cliente->email) ?></dd>

                        <dt class="col-sm-3">Telefono</dt>
                        <dd class="col-sm-9"><?= esc($cliente->telefono) ?></dd>

                        <dt class="col-sm-3">Città</dt>
                        <dd class="col-sm-9"><?= esc($cliente->citta) ?></dd>

                        <dt class="col-sm-3">Indirizzo</dt>
                        <dd class="col-sm-9"><?= esc($cliente->indirizzo) ?></dd>
                    </dl>
                </div>

                <!-- LICENZE -->
                <div class="tab-pane fade" id="licenze" role="tabpanel" aria-labelledby="licenze-tab">
                    <div class="mb-3">
                        <a href="/licenze/crea/<?= esc($cliente->id) ?>" class="btn btn-light btn-outline-secondary btn-sm text-end mb-3" title="Nuova Licenza per il cliente">
                            <i class="bi bi-key-fill"></i> Nuova Licenza
                        </a>
                    </div>
                    <?php if (!empty($licenze)): ?>
                        <table class="table table-bordered table-hover align-middle datatable" id="tabella-licenze">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Codice</th>
                                    <th>Tipo</th>
                                    <th>Modello</th>
                                    <th>Stato</th>
                                    <th class="notexport">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($licenze as $licenza): ?>
                                    <tr class="licenza-row" data-id="<?= esc($licenza->id) ?>" style="cursor:pointer;">
                                        <td><?= esc($licenza->id) ?></td>
                                        <td><?= $licenza->codice ? esc($licenza->codice) :esc($licenza->ambiente) ?></td>
                                        <td><?= esc($licenza->tipo) ?></td>
                                        <td><?= esc($licenza->modello) ?></td>
                                        <td>
                                            <span class="badge <?= $licenza->stato ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= $licenza->stato ? 'Attiva' : 'Inattiva' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/aggiornamenti/crea/<?= $licenza->id ?>/<?= $licenza->tipo ?> " class="btn btn-sm btn-outline-primary" title="Crea Aggiornamento">
                                                <i class="bi bi-clock-history"></i>
                                                <a href="/licenze/modifica/<?= $licenza->id ?>" class="btn btn-sm btn-outline-secondary" title="Modifica">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="/licenze/elimina/<?= $licenza->id ?>" class="btn btn-sm btn-outline-danger" title="Elimina" onclick=" return confirm('Sei sicuro di voler eliminare questa licenza?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Nessuna licenza associata a questo cliente.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- AGGIORNAMENTI -->
                <div class="tab-pane fade" id="aggiornamenti" role="tabpanel" aria-labelledby="aggiornamenti-tab">
                    <table class="table" id="tabella-aggiornamenti">
                        <!-- PHP per caricare gli aggiornamenti tramite fetch -->


                        <tbody>
                            <tr>
                                <td colspan="2" class="text-center class=" table-warning"">HTML Seleziona una licenza per vedere gli aggiornamenti</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> <!-- tab-content -->
        </div> <!-- card-body -->
    </div> <!-- card -->
</div> <!-- container -->

<?php $this->endSection(); ?>


<?php $this->section('scripts'); ?>
<script>
    // Event listener per il click sui tab
    document.addEventListener("DOMContentLoaded", function() {
        const licenzeRows = document.querySelectorAll('.licenza-row');
        //console.log('Licenze Rows:', licenzeRows);
        const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        //console.log('Tabs:', tabs);
        let selectedLicenzaId = null;
        // Selezione licenza
        licenzeRows.forEach(row => {
            row.addEventListener('click', function() {
                licenzeRows.forEach(r => r.classList.remove('table-primary', 'selected')); // Rimuovo classe da tutte le righe
                this.classList.add('table-primary', 'selected'); // Aggiungo classe selected alla riga selezionata per renderla univoca
                selectedLicenzaId = this.getAttribute('data-id');
                console.log('Licenza selezionata ID:', selectedLicenzaId);

            });
            row.addEventListener('dblclick', function() {
                const baseUrl = "<?= base_url() ?>";
                selectedLicenzaId = this.getAttribute('data-id');
                window.location.href = `${baseUrl}/licenze/modifica/${selectedLicenzaId}`;

            });
        });
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                let tabActive = this.id;
                //console.log('Tab attivo:', tabActive);
                switch (tabActive) {
                    /*case 'anagrafica-tab':
                        //console.log('Tab Anagrafica selezionato');
                        document.getElementById('navigation').setAttribute('href', '/clienti');
                        document.getElementById('navigation').innerHTML = '<i class="bi bi-arrow-left-circle"></i> Torna all’elenco clienti';
                        break;
                    case 'licenze-tab':
                        console.log('Tab Licenze selezionato');
                        document.getElementById('navigation').setAttribute('href', '/licenze/crea/<?= esc($cliente->id) ?>');
                        document.getElementById('navigation').innerHTML = '<i class="bi bi-key-fill"></i> Crea Licenza per il cliente';
                        break;*/
                    case 'aggiornamenti-tab':
                        /*console.log('Tab Aggiornamenti selezionato');
                        document.getElementById('navigation').setAttribute('href', '/aggiornamenti/crea/' + selectedLicenzaId);
                        document.getElementById('navigation').innerHTML = '<i class="bi bi-clock-history"></i> Aggiungi Aggiornamento per la licenza';*/
                        let tabAggContentBody = document.querySelector('#tabella-aggiornamenti tbody');
                        tabAggContentBody.innerHTML = ''; // Pulisce il contenuto della tabella
                        if (!selectedLicenzaId) {
                            document.querySelector('#tabella-aggiornamenti tbody').innerHTML = '<tr><td colspan="2" class="text-center bg-alert bg-gradient table-warning"> Seleziona una licenza per vederne gli aggiornamenti.</td></tr>';
                            return;
                        } else {
                            fetch(`/aggiornamenti/index/${selectedLicenzaId}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Errore nel caricamento degli aggiornamenti');
                                    }
                                    return response.text(); //Ricevo il contenuto HTML
                                })
                                .then(html => {
                                    tabAggContentBody.innerHTML = html; // Inserisco direttamente il contenuto HTML nel tbody
                                })
                                .catch(err => { //Intercetto errori
                                    tabAggContentBody.innerHTML = '<tr><td colspan="2" class="text-danger text-center">Errore nel caricamento aggiornamenti.</td></tr>';
                                    console.error(err);
                                });
                        }
                        break;
                    default:
                        console.log('Tab sconosciuto selezionato');
                }
            });
        });
    });
</script>
<?php $this->endSection(); ?>