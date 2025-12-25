<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">


    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people"></i> Elenco Clienti</h5>
            <a href="<?= base_url("/clienti/crea") ?>" class="btn btn-light btn-outline-secondary btn-sm" title="Aggiungi nuovo cliente">
                <i class="bi bi-person-add"></i>
                Nuovo Cliente
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
                        <div class="d-flex justify-content-end align-items-center gap-3" id="licenze">
                            <div class="form-check">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="licenze" value="tutti"
                                        id="licenzeTutti" checked>
                                    <label class="form-check-label" for="licenzeTutti">Tutti i clienti</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="licenze" value="si"
                                        id="licenzeSi">
                                    <label class="form-check-label" for="licenzeSi">Con Licenze attive</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="licenze" value="no"
                                        id="licenzeNo">
                                    <label class="form-check-label" for="licenzeNo">Senza Licenze attive</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div> <!-- row filtri -->

                <?php if (!empty($clienti)): ?>

                    <table class="table table-striped table-hover align-middle datatable" id="clientiTable">
                        <thead class="table-light">
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clienti as $cliente): ?>
                                <tr class="cliente-row" data-id="<?= esc($cliente->id) ?>">
                                    <td><?= esc($cliente->id) ?></td>
                                    <td><?= esc($cliente->codice) ?></td>
                                    <td><?= esc($cliente->nome) ?></td>
                                    <td><?= esc($cliente->email) ?></td>
                                    <td><?= esc($cliente->telefono) ?></td>
                                    <td><?= esc($cliente->citta) ?></td>
                                    <td>
                                        <?php if ($cliente->numLicenze > 0):
                                        ?>
                                            <span class="badge bg-success">
                                                <?php echo $cliente->numLicenze ?> </span>
                                        <?php else:
                                        ?>
                                            <span class="badge bg-secondary">0</span>
                                        <?php endif;
                                        ?>
                                    </td>
                                    <td>
                                        <?php // Visualizzo i tipi di licenze come lista
                                        foreach ($cliente->tipiLicenze as $tipo): ?>
                                            <span class="badge bg-transparent text-dark mb-1"><?= esc($tipo) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <button class="btn dropdown-toggle" type="button" id="azione" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-list"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a class="dropdown-item" href="<?= site_url('/clienti/schedaCliente/' . $cliente->id) ?>">
                                                    <i class="bi bi-person-vcard"></i>
                                                    Scheda Cliente
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="<?= site_url('clienti/modifica/' . $cliente->id) ?>">
                                                    <i class="bi bi-pencil"></i>
                                                    Modifica
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li class="">
                                                <a class="dropdown-item text-danger" href="<?= site_url('clienti/elimina/' . $cliente->id) ?>">
                                                    <i class="bi bi-trash"></i>
                                                    Elimina
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
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
        </div>
    </div>
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
                window.location.href = `${baseUrl}/clienti/schedaCliente/${selectedClienteId}`;
            });
        });
        // inizializza la DataTable 
        const table = $('#clientiTable').DataTable($.extend(true, {}, datatableDefaults, {
            order: [],
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