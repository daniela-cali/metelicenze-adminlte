<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container my-5">


    <div class="card shadow-sm">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people"></i> Elenco Fornitori</h5>
            <a href="<?= url_to('fornitori_new') ?>" class="btn btn-light btn-outline-secondary btn-sm" title="Aggiungi nuovo fornitore">
                <i class="bi bi-person-add"></i>
                Nuovo Fornitore
            </a>
        </div>
        <div class="card-body">
            <div class="container"> <!-- CONTAINER FILTRI -->
                <div class="row"> <!-- TIPO -->
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
                                <input type="checkbox" name="tipi" value="firma" id="tipoFirma">
                                <label class="form-check-label" for="tipoFirma">Servizio Firma</label>
                            </div>
                        </div>
                    </div>
                </div> <!-- END TIPO -->
                

                <?php if (!empty($fornitori)): ?>
                    <?php //dd($fornitori) ?>

                    <table class="table table-striped table-hover align-middle datatable" id="clientiTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Codice Fornitore</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefono</th>
                                <th class="notexport">Città</th>
                                <th>Tipi</th>
                                <th class="notexport">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fornitori as $fornitore): ?>
                                <tr class="fornitore-row" 
                                data-id="<?= esc($fornitore["id"]) ?>"
                                data-bs-toggle="tooltip"
                                data-bs-placement= "right"
                                title="Creato da: <?= $fornitore["created_by_name"] ?> il <?= date('d/m/Y H:i', strtotime($fornitore["created_at"])) ?>
                                <?php if ($fornitore["updated_at"]): ?>
                                     Ultima modifica da: <?= $fornitore["updated_by_name"] ?> il <?= date('d/m/Y H:i', strtotime($fornitore["updated_at"])) ?>
                                <?php endif; ?> 
                                ">
                                    <td><?= esc($fornitore["id"]) ?></td>
                                    <td><?= esc($fornitore["codice"]) ?></td>
                                    <td><?= esc($fornitore["nome"]) ?></td>
                                    <td><?= esc($fornitore["email"]) ?></td>
                                    <td><?= esc($fornitore["telefono"]) ?></td>
                                    <td><?= esc($fornitore["citta"]) ?></td>

                                    <td>
                                        <?php // Visualizzo i tipi di licenze fornite come lista
                                        foreach ($fornitore["tipiLicenze"] as $tipo): ?>
                                            <span class="badge bg-transparent text-dark mb-1"><?= esc($tipo["nome"]) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <button class="btn dropdown-toggle" type="button" id="azione" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-list"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="azioniDropDownMenu">
                                            <li>
                                                <a class="dropdown-item" href="<?= url_to('fornitori_show', $fornitore["id"]);  ?>">
                                                    <i class="bi bi-person-vcard"></i>
                                                    Scheda Fornitore
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item" href="<?= url_to('fornitori_edit', $fornitore["id"]); ?>">
                                                    <i class="bi bi-pencil"></i>
                                                    Modifica
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li class="">
                                                <a class="dropdown-item text-danger" href="<?= url_to('fornitori_delete', $fornitore["id"]); ?>">
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
                        <i class="bi bi-info-circle"></i> Nessun fornitore trovato nel database.
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
        const fornitoriRows = document.querySelectorAll('.fornitore-row');
        let selectedFornitoreId = null;
        fornitoriRows.forEach(row => {
            row.addEventListener('click', function() {
                fornitoriRows.forEach(r => r.classList.remove('table-primary', 'selected'));
                selectedFornitoreId = this.getAttribute('data-id');
                console.log("Cliente selezionato: " + selectedFornitoreId);
                this.classList.add('table-primary', 'selected');
            });
            row.addEventListener('dblclick', function() {
                selectedFornitoreId = this.getAttribute('data-id');
                const baseUrl = "<?= base_url() ?>";
                selectedFornitoreId = this.getAttribute('data-id');
                console.log("Redirecting to cliente ID: " + selectedFornitoreId);
                window.location.href = `${baseUrl}/fornitori/${selectedFornitoreId}`;
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