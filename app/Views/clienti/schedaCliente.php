<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4 mb-4" id="scheda-cliente">

    <div class="container-fluid mt-4 p-0">
        <h1 class="display-6 mb-0"><i class="bi bi-people-fill"></i> Scheda Cliente</h1>
        <p class="lead mb-0">Dettagli e gestione del cliente</p>
        <nav id="anchor-nav" class="navbar navbar-expand-lg navbar-light bg-light anchor-nav rounded shadow-sm mt-3 mb-3 p-2">

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#anagrafica">Anagrafica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#licenze">Licenze</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#aggiornamenti">Aggiornamenti</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li>
                        <a href="<?= previous_url() ?>" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left-circle"></i> Torna indietro
                        </a>
                    </li>
                </ul>

            </div>
        </nav>
    </div>

</div>

<!--Card Anagrafica-->
<div class="container-fluid mt-4 p-0" id="anagrafica">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Dati Anagrafici</h5>
            <a href="<?= base_url("/clienti/modifica/" . $cliente->id) ?>" class="btn btn-light btn-outline-secondary btn-sm" title="Modifica">
                Modifica <i class="bi bi-pencil"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <!-- COLONNA SINISTRA -->
                    <dl class="dl-kv">
                        <dt>Codice Cliente</dt>
                        <dd><?= esc($cliente->codice) ?></dd>

                        <dt>Ragione Sociale</dt>
                        <dd><?= esc($cliente->nome) ?></dd>

                        <dt>Codice Cliente</dt>
                        <dd class=><?= esc($cliente->codice) ?></dd>

                        <dt>Nome</dt>
                        <dd class=><?= esc($cliente->nome) ?></dd>

                        <dt>Partita IVA</dt>
                        <dd class=><?= esc($cliente->piva) ?></dd>

                        <dt>Indirizzo</dt>
                        <dd class=><?= esc($cliente->indirizzo) ?></dd>

                        <dt>CAP</dt>
                        <dd class=><?= esc($cliente->cap) ?></dd>

                        <dt>Città</dt>
                        <dd class=><?= esc($cliente->citta) ?></dd>

                        <dt>Provincia</dt>
                        <dd class=><?= esc($cliente->provincia) ?></dd>

                    </dl>
                </div>
                <!-- COLONNA DESTRA -->
                <div class="col-12 col-md-6">
                    <dl class="dl-kv">
                        <dt>Email</dt>
                        <dd class=><?= esc($cliente->email) ?></dd>

                        <dt>Telefono</dt>
                        <dd class=><?= esc($cliente->telefono) ?></dd>

                        <dt>Contatti</dt>
                        <dd class=><?= esc($cliente->contatti) ?></dd>

                        <dt>Note</dt>
                        <dd class=><?= esc($cliente->note) ?></dd>


                        <dt>Cliente Interno</dt>
                        <dd class=><?= $cliente->figlio_sn ? 'Sì' : 'No' ?></dd>

                        <dt>Cliente Padre</dt>
                        <dd class="pointer" id="cliente-padre" data-id="<?= $cliente->padre_id ? $cliente->padre_id : '' ?>">
                            <?= $cliente->padre_id ? esc($cliente->padre_nome) . ' [ID: ' . esc($cliente->padre_id) . ']' : '-' ?>
                        </dd>
                </div>

            </div>
            <a href="#scheda-cliente" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-up-square"></i> Torna in cima
            </a>
        </div> <!--Fine Card Body Anagrafica-->
    </div> <!--Fine Card Anagrafica-->
</div> <!--Fine Container Anagrafica-->



<!--Card Licenze-->
<div class="container-fluid mt-4 p-0" id="licenze">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-key-fill"></i> Licenze</h5>
            <a href="<?= base_url("/licenze/crea/" . $cliente->id) ?>" class="btn btn-light btn-outline-secondary btn-sm" title="Nuova Licenza per il cliente">
                <i class="bi bi-key-fill"></i> Nuova Licenza
            </a>
        </div>
        <div class="card-body">
            <?php if (!empty($licenze)): ?>
                <table class="table table-bordered table-striped table-hover align-middle datatable" id="tabella-licenze">
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
                            <tr class="licenza-row" data-id="<?= esc($licenza->padre_lic_id) //linko il padre per il fetch aggiornamenti 
                                                                ?>" style="cursor:pointer;">
                                <td><?= esc($licenza->id) ?></td>
                                <td><?= $licenza->codice ? esc($licenza->codice) : esc($licenza->ambiente) ?></td>
                                <td><?= esc($licenza->tipo) ?></td>
                                <td><?= esc($licenza->modello) ?></td>
                                <td>
                                    <span class="badge <?= $licenza->stato ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= $licenza->stato ? 'Attiva' : 'Inattiva' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/aggiornamenti/crea/<?= $licenza->padre_lic_id ?>/<?= $licenza->tipo ?> " class="btn btn-sm btn-outline-primary" title="Crea Aggiornamento">
                                        <i class="bi bi-clock-history"></i>
                                        <a href="/licenze/modifica/<?= $licenza->id //Modifico la licenza stessa
                                                                    ?>" class="btn btn-sm btn-outline-secondary" title="Modifica">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/licenze/elimina/<?= $licenza->id //Elimino la licenza stessa
                                                                    ?>" class="btn btn-sm btn-outline-danger" title="Elimina" onclick=" return confirm('Sei sicuro di voler eliminare questa licenza?');">
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
            <a href="#scheda-cliente" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-up-square"></i> Torna in cima
            </a>
        </div>
    </div>
</div> <!--Fine Card Licenze-->

<!--Card Aggiornamenti-->
<div class="container-fluid mt-4 p-0" id="aggiornamenti">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Aggiornamenti</h5>

        </div>
        <div class="card-body">

            <table class="table table-bordered table-striped table-hover align-middle datatable" id="tabella-aggiornamenti">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data aggiornamento</th>
                        <th>Versione</th>
                        <th>Note</th>
                        <th>Azioni</th>
                    </tr>
                <tbody>

                </tbody>
            </table>
            <a href="#scheda-cliente" id="navigation" class="btn btn-light btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-up-square"></i> Torna in cima
            </a>
        </div>
    </div>
</div> <!--Fine Card Aggiornamenti-->




<?php $this->endSection(); ?>


<?php $this->section('scripts'); ?>
<script>
    function actionButtons(id) {
        return `
            <a href="/aggiornamenti/visualizza/${id}" class="btn btn-sm btn-outline-primary" title="Visualizza">
                <i class="bi bi-eye"></i>       
            </a>
            <a href="/aggiornamenti/modifica/${id}" class="btn btn-sm btn-outline-secondary" title="Modifica">
                <i class="bi bi-pencil"></i>        
            </a>
            <a href="/aggiornamenti/elimina/${id}" class="btn btn-sm btn-outline-danger" title="Elimina" onclick=" return confirm('Sei sicuro di voler eliminare questo aggiornamento?');">
                <i class="bi bi-trash"></i>     
            </a>
            `;
    }
    document.addEventListener("DOMContentLoaded", function() {

        const cliente_padre = document.querySelector('#cliente-padre');

        cliente_padre.addEventListener('dblclick', function() {
            const padreId = this.getAttribute('data-id');
            <?php log_message('info', 'View schedaCliente: doppio click cliente padre ID: ' . $cliente->padre_id);
            log_message('info', 'Attuale URL: ' . current_url()); ?>
            if (!padreId) return;
            const baseUrl = "<?= base_url() ?>";
            window.location.href = `${baseUrl}/clienti/schedaCliente/${padreId}`;
        });

        const MSG_SELECT = "Seleziona una licenza per visualizzare gli aggiornamenti associati.";
        const MSG_EMPTY = "Non ci sono aggiornamenti per questa licenza.";

        const tabellaAggiornamenti = $('#tabella-aggiornamenti').DataTable({
            paging: false,
            info: false,
            searching: false,
            ordering: false,
            processing: true,

        });

        function setEmptyMessage(msg) {
            tabellaAggiornamenti.settings()[0].oLanguage.sEmptyTable = msg;
            tabellaAggiornamenti.draw(false);
        }

        // all’avvio: “Seleziona…”
        setEmptyMessage(MSG_SELECT);

        $('#tabella-aggiornamenti')
            .on('xhr.dt', function(e, settings, json, xhr) {
                const rows = (json && Array.isArray(json.data)) ? json.data : [];
                if (rows.length === 0) setEmptyMessage(MSG_EMPTY);
                else setEmptyMessage(MSG_SELECT);

            })
            .on('error.dt', function(e, settings, techNote, message) {
                console.error('DT error:', message);
            });
        // Event listener per il click sulle righe delle licenze
        const licenzeRows = document.querySelectorAll('.licenza-row');
        //console.log('Licenze Rows:', licenzeRows);
        let selectedLicenzaId = null;
        // Selezione licenza
        licenzeRows.forEach(row => {
            row.addEventListener('click', function() {
                licenzeRows.forEach(r => r.classList.remove('table-primary', 'selected')); // Rimuovo classe da tutte le righe
                this.classList.add('table-primary', 'selected'); // Aggiungo classe selected alla riga selezionata per renderla univoca
                selectedLicenzaId = this.getAttribute('data-id');
                console.log('Licenza selezionata ID:', selectedLicenzaId);
                // Effettuo la chiamata AJAX per ottenere gli aggiornamenti della licenza selezionata
                if (selectedLicenzaId) {
                    console.log('Caricamento aggiornamenti per licenza ID:', selectedLicenzaId);
                    fetch(`<?= base_url('aggiornamenti/byLicenza') ?>/${selectedLicenzaId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Errore recupero aggiornamenti ' + response.status + ' ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(payload => {
                            console.log('Aggiornamenti ricevuti (payload):', payload);
                            console.log('Tipo payload:', typeof payload);
                            console.log('Dati aggiornamenti:', payload.data.length);

                            const rows = payload.data ?? []; // <-- array di oggetti

                            tabellaAggiornamenti.clear();

                            rows.forEach(aggiornamento => {

                                const rowNode = tabellaAggiornamenti.row.add([
                                    aggiornamento.id,
                                    aggiornamento.dt_agg,
                                    aggiornamento.versione,
                                    aggiornamento.note,
                                    actionButtons(aggiornamento.id)
                                ]).draw(false).node();
                                rowNode.classList.add('aggiornamento-row');
                                rowNode.dataset.id = aggiornamento.id;

                            });
                            if (rows.length === 0) {
                                setEmptyMessage(MSG_EMPTY);
                            }
                            tabellaAggiornamenti.draw();
                        })
                        .catch(error => {
                            console.error('Errore nel recupero degli aggiornamenti:', error);
                        });

                } else {
                    // Nessuna licenza selezionata, svuoto la tabella
                    tabellaAggiornamenti.clear().draw();
                    setEmptyMessage(MSG_SELECT);
                }
            });

            $('#tabella-aggiornamenti').on('dblclick', '.aggiornamento-row', function(e) {
                // evita che il click sui bottoni scatti anche sulla riga
                if (e.target.closest('button')) return;

                const selectedAggiornamento = this.dataset.id;
                console.log('Click riga aggiornamento', selectedAggiornamento);
                const baseUrl = "<?= base_url() ?>";
                window.location.href = `${baseUrl}/aggiornamenti/modifica/${selectedAggiornamento}`;
            });
            row.addEventListener('dblclick', function() {
                const baseUrl = "<?= base_url() ?>";
                selectedLicenzaId = this.getAttribute('data-id');
                window.location.href = `${baseUrl}/licenze/modifica/${selectedLicenzaId}`;

            });
        });

    });
</script>
<?php $this->endSection(); ?>