<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Fornitori</li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-people"></i> Elenco Fornitori</h5>
    <a href="<?= url_to('fornitori_create') ?>" class="btn btn-outline-secondary btn-sm" title="Aggiungi nuovo fornitore">
        <i class="bi bi-person-add"></i> Nuovo Fornitore
    </a>
</div>
<div class="row g-3 mb-3">
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
                <input class="form-check-input" type="checkbox" name="tipi" value="firma" id="tipoFirma">
                <label class="form-check-label" for="tipoFirma">Servizio Firma</label>
            </div>
        </div>
    </div>
</div>
<div>


                <?php if (!empty($fornitori)): ?>
                    <?php //dd($fornitori) ?>

                    <table class="table table-bordered table-striped table-hover align-middle datatable" id="primaryTable">
                        <thead class="table-secondary">
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
                                <tr class="data-row"
                                data-id="<?= esc($fornitore["id"]) ?>"
                                <?= audit_tooltip($fornitore, 'right') ?>>
                                    <td><?= esc($fornitore["id"]) ?></td>
                                    <td><?= esc($fornitore["codice"]) ?></td>
                                    <td><?= esc($fornitore["nome"]) ?></td>
                                    <td><?= esc($fornitore["email"]) ?></td>
                                    <td><?= esc($fornitore["telefono"]) ?></td>
                                    <td><?= esc($fornitore["citta"]) ?></td>

                                    <td>
                                        <?php // Visualizzo i tipi di licenze fornite come lista
                                        foreach ($fornitore["tipiLicenze"] as $tipo): 
                                            $tipoLicenza = $tipo["modello"] !== 'Unico' ? $tipo["nome"]. ' - ' . $tipo["modello"] : $tipo["nome"];
                                        ?>

                                            <span class="badge d-block bg-transparent text-dark mb-1"><?= esc($tipoLicenza) ?></span>
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

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // inizializza la DataTable 
        const table = $('#primaryTable').DataTable($.extend(true, {}, datatableDefaults, {
            order: [],
        }));

        // filtro custom: licenze + tipi
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'primaryTable') return true;

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
            const tipiCellText = $(rowNode).find('td').eq(6).text().trim();
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