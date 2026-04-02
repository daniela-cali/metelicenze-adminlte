<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Clienti</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-people"></i> Elenco Clienti</h5>
    <a href="<?= url_to('clienti_crea') ?>" class="btn btn-outline-secondary btn-sm" title="Aggiungi nuovo cliente">
        <i class="bi bi-person-add"></i> Nuovo Cliente
    </a>
</div>
<div class="row g-3 mb-3">
    <!-- Colonna: Tipo licenza -->
    <div class="col-auto">
        <small class="text-muted text-uppercase fw-semibold d-block mb-1">Tipo licenza</small>
        <div class="d-flex gap-3" id="tipi">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tipi" value="Sigla" id="tipoSigla">
                <label class="form-check-label" for="tipoSigla">Sigla</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tipi" value="VarHub" id="tipoVarHub">
                <label class="form-check-label" for="tipoVarHub">VarHub</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tipi" value="SKNT" id="tipoSKNT">
                <label class="form-check-label" for="tipoSKNT">SKNT</label>
            </div>
        </div>
    </div>

    <!-- Separatore verticale -->
    <div class="col-auto d-none d-sm-flex align-items-end pb-1">
        <div class="vr"></div>
    </div>

    <!-- Colonna: Stato licenze -->
    <div class="col-auto">
        <small class="text-muted text-uppercase fw-semibold d-block mb-1">Stato licenze</small>
        <div class="d-flex gap-3" id="licenze">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="licenze" value="tutti" id="licenzeTutti" checked>
                <label class="form-check-label" for="licenzeTutti">Tutti</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="licenze" value="si" id="licenzeSi">
                <label class="form-check-label" for="licenzeSi">Con licenze</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="licenze" value="no" id="licenzeNo">
                <label class="form-check-label" for="licenzeNo">Senza licenze</label>
            </div>
        </div>
    </div>
</div>
<div>

                <?php if (!empty($clienti)): ?>
                    <?php //dd($clienti) ?>

                    <table class="table table-bordered table-striped table-hover align-middle datatable" id="clientiTable">
                        <thead class="table-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Codice cliente</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefono</th>
                                <th class="notexport">Città</th>
                                <th>N°</th>
                                <th>Tipi</th>
                                <th class="notexport">Azioni</th>
                                <th class="notexport">Gruppo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clienti as $cliente): ?>
                                <tr class="cliente-row"
                                data-id="<?= esc($cliente["id"]) ?>"
                                <?= audit_tooltip($cliente) ?>>
                                    <td><?= esc($cliente["id"]) ?></td>
                                    <td><?= esc($cliente["codice"]) ?></td>
                                    <td><?= esc($cliente["nome"]) ?></td>
                                    <td><?= esc($cliente["email"]) ?></td>
                                    <td><?= esc($cliente["telefono"]) ?></td>
                                    <td><?= esc($cliente["citta"]) ?></td>
                                    <td>
                                        <?php if ($cliente["numLicenze"] > 0):
                                        ?>
                                            <span class="badge bg-success">
                                                <?php echo $cliente["numLicenze"] ?> </span>
                                        <?php else:
                                        ?>
                                            <span class="badge bg-secondary">0</span>
                                        <?php endif;
                                        ?>
                                    </td>
                                    <td>
                                        <?php // Visualizzo i tipi di licenze come lista
                                        foreach ($cliente["tipiLicenze"] as $tipo): ?>
                                            <span class="badge d-block bg-transparent text-dark"><?= esc($tipo) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <button class="btn dropdown-toggle" type="button" id="azione" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-list"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" href="<?= url_to('clienti_scheda', $cliente["id"]) ?>">
                                                    <i class="bi bi-person-vcard"></i>
                                                    Scheda Cliente
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="<?= url_to('clienti_modifica', $cliente["id"]) ?>">
                                                    <i class="bi bi-pencil"></i>
                                                    Modifica
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li class="">
                                                <a class="dropdown-item text-danger" href="<?= url_to('clienti_elimina', $cliente["id"]) ?>">
                                                    <i class="bi bi-trash"></i>
                                                    Elimina
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                    <td><?= esc($cliente["gruppo"]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Nessun cliente trovato nel database.
                    </div>
                <?php endif; ?>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //$(document).ready(function () {
        const clientiRows = document.querySelectorAll('.cliente-row');
        let selectedClienteId = null;
        clientiRows.forEach(row => {
            row.addEventListener('click', function() {
                clientiRows.forEach(r => r.classList.remove('table-primary', 'selected'));
                selectedClienteId = this.getAttribute('data-id');
                console.log("Cliente selezionato: " + selectedClienteId);
                this.classList.add('table-primary', 'selected');
            });
            row.addEventListener('dblclick', function() {
                selectedClienteId = this.getAttribute('data-id');
                const baseUrl = "<?= base_url() ?>";
                selectedClienteId = this.getAttribute('data-id');
                console.log("Redirecting to cliente ID: " + selectedClienteId);
                window.location.href = `${baseUrl}/clienti/${selectedClienteId}`;
            });
        });
        // Pre-calcola i conteggi per gruppo dall'HTML PRIMA che DataTables inizializzi
        // la tabella — così abbiamo i totali reali, indipendenti dalla paginazione.
        const gruppiCount = {};
        $('#clientiTable tbody tr').each(function() {
            const gruppo = $(this).find('td').eq(9).text().trim();
            if (gruppo) gruppiCount[gruppo] = (gruppiCount[gruppo] || 0) + 1;
        });

        // inizializza la DataTable
        const table = $('#clientiTable').DataTable($.extend(true, {}, datatableDefaults, {
            order: [],
            columnDefs: [
                { targets: 9, visible: false } // colonna "Gruppo" nascosta, usata solo per raggruppamento
            ],
            orderFixed: { pre: [[9, 'asc']] }, // forza sort per gruppo come primo criterio
            drawCallback: function() {
                // Tooltip (ripetuto perché questo drawCallback sovrascrive quello dei defaults)
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                    bootstrap.Tooltip.getOrCreateInstance(el);
                });

                // Aggiorna i conteggi rispettando i filtri attivi
                const api = this.api();
                const gruppiCountFiltered = {};
                api.rows({ search: 'applied' }).every(function() {
                    const gruppo = this.data()[9];
                    gruppiCountFiltered[gruppo] = (gruppiCountFiltered[gruppo] || 0) + 1;
                });

                // Inietta header di gruppo, bordo di chiusura e classe sui membri
                let lastGruppo = null;
                let lastNode = null;
                api.rows({ page: 'current' }).every(function() {
                    const gruppo = this.data()[9];
                    const node = this.node();
                    if (gruppo !== lastGruppo) {
                        if (lastNode && gruppiCountFiltered[lastGruppo] > 1) {
                            $(lastNode).addClass('group-last');
                        }
                        if (gruppiCountFiltered[gruppo] > 1) {
                            $(node).before(
                                '<tr class="group-header"><td colspan="9"><strong>' + gruppo + '</strong></td></tr>'
                            );
                        }
                        lastGruppo = gruppo;
                    }
                    // Marca le righe che appartengono a un gruppo reale (toglie lo striped)
                    if (gruppiCountFiltered[gruppo] > 1) {
                        $(node).addClass('group-member');
                    } else {
                        $(node).removeClass('group-member');
                    }
                    lastNode = node;
                });
                if (lastNode && gruppiCountFiltered[lastGruppo] > 1) {
                    $(lastNode).addClass('group-last');
                }
            }
        }));

        // filtro custom: licenze + tipi
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'clientiTable') return true;

            // --- filtro "licenze" (colonna N° index 6)
            const licenzeVal = $('input[name="licenze"]:checked').val(); // 'si'|'no'|undefined
            const rowNode = table.row(dataIndex).node();
            //console.log("Valore licenze selezionato: " + licenzeVal);
            const numLicenzeText = $(rowNode).find('td').eq(6).text().trim();
            //console.log("Numero licenze cella: " + numLicenzeText);
            const numLicenze = parseInt(numLicenzeText.replace(/\D/g, '')) || 0; // estrai numero
            if (licenzeVal === 'tutti') return true; // nessun filtro licenze
            if (licenzeVal === 'si' && numLicenze <= 0) return false;
            if (licenzeVal === 'no' && numLicenze > 0) return false;

            // --- filtro "tipi" (colonna Tipi index 7)
            const selectedTipi = $('input[name="tipi"]:checked').map(function() {
                return $(this).val();
            }).get();
            if (selectedTipi.length === 0) return true; // nessun filtro tipi
            const tipiCellText = $(rowNode).find('td').eq(7).text().trim();
            // includi se ALMENO un tipo selezionato è presente nella cella (OR)
            return selectedTipi.some(t => tipiCellText.indexOf(t) !== -1);
        });

        // quando cambia un filtro, ridisegna la tabella 
        $('input[name="tipi"]').on('change', function() {
            $(":radio[value=no][name=licenze]").prop("checked", false);
            $(":radio[value=si][name=licenze]").prop("checked", true);
            table.draw();
        });
        $('input[name="licenze"]').on('change', function() {
            //leggo il valore selezionato e disabilito le checkbox dei tipi se "no" per coerenza di interfaccia
            let val = this.value;
            if (val == 'no') {
                $('input[name="tipi"]').prop('checked', false);
            }
            table.draw();
        });
    });
</script>
<?= $this->endSection() ?>