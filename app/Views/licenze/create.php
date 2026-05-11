<?php

/**
 * @var string $title
 * @var string  $mode
 * @var array  $form
 * @var array  $selectValues
 * @var string $backTo
 * @var string $licenze
 * ...
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<ol class="breadcrumb float-sm-end">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?= url_to('licenze_index') ?>">Licenze</a></li>
    <li class="breadcrumb-item active"><?= esc($title) ?></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-header card-header-muted d-flex align-items-center">
        <h5 class="mb-0"><i class="bi bi-key-fill"></i> <?= esc($title) ?></h5>
        <a href="<?= esc($backTo) ?>" class="btn btn-light btn-sm ms-auto">
            <i class="bi bi-arrow-left"></i> Indietro
        </a>
    </div>

    <div class="card-body">
        <!--Aggiungo la modalità di creazione o modifica per il js-->
        <form id="createForm" action="<?= $form["action"] ?>" method="post" data-mode="<?= $mode ?>">
            <input type="hidden" name="backTo" value="<?= esc($backTo) ?>">
            <input type="hidden" name="clienti_id" value="<?= esc($licenza['clienti_id'] ?? $id_cliente ?? '') ?>">
            <?php if ($mode === 'edit'): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            <div class="mb-3 form-check" data-categoria="Common">
                <input type="hidden" name="figlio_sn" value="0" />
                <label class="form-check-label" class="form-label" for="figlio_sn">Licenza Figlio</label>
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="figlio_sn"
                    name="figlio_sn"
                    value="1"
                    <?= (isset($licenza) && $licenza["figlio_sn"]) ? 'checked' : '' ?>>
                </input>
            </div>
            <div class="mb-3 d-none">
                <label for="padre_id" class="form-label">Licenza Padre</label>
                <select name="padre_id" id="padre_id" class="form-select" <?= ((isset($licenza) && $licenza["figlio_sn"]) || ($mode == 'create')) ? '' : 'disabled' ?>>
                    <option value="">-- Seleziona --</option>
                    <?php foreach ($selectValues as $option): ?>
                        <option value="<?= esc($option["value"]) ?>"
                            <?= (isset($licenza) && $licenza["padre_id"] == $option["value"]) ? 'selected' : '' ?>>
                            <?= esc($option["content"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3" data-categoria="Common">
                <label for="tipilicenze_id" class="form-label">Tipo</label>
                <?= view_cell('TipiCell::select', ['selezionato' => $licenza['tipilicenze_id'] ?? null]) ?>
            </div>
            <?php if (isset($licenza)): ?>
                <input type="hidden" name="padre_lic_id" id="padre_lic_id" value="<?= isset($licenza["padre_lic_id"]) ? esc($licenza["padre_lic_id"]) : esc($licenza["id"]) ?>">
            <?php endif; ?>


            <div class="mb-3 d-none" data-categoria="gest_contab,firma_digitale">
                <label for="codice" class="form-label">Codice Licenza</label>
                <input type="text" name="codice" id="codice" class="form-control" required placeholder="Es. ABC12345"
                    value="<?= isset($licenza) ? esc($licenza["codice"]) : '' ?>">
            </div>
            <div class="mb-3 d-none" data-categoria="fatt_elett">
                <label for="ambiente" class="form-label">Ambiente</label>
                <input type="text" name="ambiente" id="ambiente" class="form-control" required placeholder="Es. usr_12345"
                    value="<?= isset($licenza) ? esc($licenza["ambiente"]) : '' ?>">
            </div>
            <div class="mb-3 d-none" data-categoria="fatt_elett">
                <label for="nodo" class="form-label">Nodo</label>
                <input type="text" name="nodo" id="nodo" class="form-control" required placeholder="CLNT_12345XYZ"
                    value="<?= isset($licenza) ? esc($licenza["nodo"]) : '' ?>">
            </div>
            <div class="mb-3 d-none" data-categoria="fatt_elett">
                <label for="invii" class="form-label">Invii</label>
                <input type="text" name="invii" id="invii" class="form-control" required placeholder="500"
                    value="<?= isset($licenza) ? esc($licenza["invii"]) : 500 ?>">
            </div>
            <div class="mb-3 d-none" data-categoria="fatt_elett">
                <label for="giga" class="form-label">Giga</label>
                <input type="text" name="giga" id="giga" class="form-control" required placeholder="Invii * 2"
                    value="<?= isset($licenza) ? esc($licenza["giga"]) : 500 ?>">
            </div>
            <div class="mb-3 d-none" data-categoria="gest_contab">
                <label for="postazioni" class="form-label">Postazioni Licenza</label>
                <input type="text" name="postazioni" id="postazioni" class="form-control" required
                    value="<?= isset($licenza) ? esc($licenza["postazioni"]) : 1 ?>" />
            </div>

            <div class="mb-3 form-group d-none" data-categoria="Common">
                <label for="server">Server</label>
                <input type="text" class="form-control" id="server" name="server" placeholder="192.168.0.1, localhost, ecc." value="<?= isset($licenza) ? esc($licenza["server"]) : '' ?>">
                </input>
            </div>
            <div class="mb-3 form-group d-none" data-categoria="gest_contab">
                <label for="conn">Connessioni</label>
                <textarea class="form-control" id="conn" name="conn" rows="3"><?=
                                                                                isset($licenza) ? esc($licenza["conn"]) : ''
                                                                                ?></textarea>
            </div>

            <div class="mb-3 form-group" data-categoria="Common">
                <label for="note">Note Licenza</label>
                <textarea class="form-control" id="note" name="note" rows="3"><?=
                                                                                isset($licenza) ? esc($licenza["note"]) : ''
                                                                                ?></textarea>
            </div>
            <div class="mb-3 form-check" data-categoria="Common">
                <label class="form-check-label" class="form-label" for="stato">Licenza attiva</label>
                <!-- Per gestire il checkbox in modo che invii sempre un valore -->
                <input type="hidden" name="stato" value="0" />
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="stato"
                    name="stato"
                    value="1"
                    <?php if (!isset($licenza) || $licenza["stato"]) echo 'checked'; ?> />
            </div>

            <div class="mt-4" data-categoria="Common">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Salva
                </button>
                <a href="<?= esc($backTo) ?>" class="btn btn-secondary">Annulla</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {

		function getCategoriaSelezionata() {
				return selectTipo.options[selectTipo.selectedIndex] ?.dataset.optCategoria ?? '';
		}

		function aggiornaCampi() {
			const categoriaSelezionata = getCategoriaSelezionata();

			document.querySelectorAll("[data-categoria]").forEach(wrapper => {
				const valoreWrapper = wrapper.getAttribute("data-categoria");
				const categorie = valoreWrapper.split(',').map(s => s.trim());

				if (valoreWrapper === "Common" || categorie.includes(categoriaSelezionata)) {
					wrapper.classList.remove("d-none");

				} else {
					wrapper.classList.add("d-none");
					wrapper.querySelectorAll("input, select, textarea").forEach(campo => {
						campo.required = false;
						campo.disabled = true;
					});
				}
			});
		}

        const selectTipo = document.getElementById("tipilicenze_id");
        selectTipo.addEventListener('change', aggiornaCampi);


		function hideSelect() { //Faccio il toggle del campo in base alla classe
			const element = document.getElementById('padre_id');
			const wrapper = element.parentElement;
			wrapper.classList.toggle('d-none');
			if (wrapper.classList.contains('d-none')) { //Azzero il valore della select se tolgo figlio
				element.value = '';
			}
		}
		const figlio_sn = document.getElementById("figlio_sn");
		//figlio_sn.addEventListener('click', (hideSelect));       
		figlio_sn.addEventListener('click', (e) => {
			hideSelect();
		});

		const selectPadreId = document.getElementById("padre_id");
		selectPadreId.addEventListener('change', (e) => getDataLicenzaPadre(e.target));

		function setFormValues(formName, values = 0) {
			//const form = document.forms[formName];
			//const inputs = form.elements;
			document.getElementById('tipilicenze_id').value = values.tipilicenze_id;
			document.getElementById('codice').value = values.codice;
			document.getElementById('postazioni').value = values.postazioni;
			document.getElementById('server').value = values.server;
			document.getElementById('conn').value = values.conn;
			document.getElementById('note').value = values.note;
			aggiornaCampi();
		};

		function getDataLicenzaPadre(element) {

			padreID = element.value;
			//alert(padreID);

			fetch(`<?= base_url('licenze/byID') ?>/${padreID}`)
				.then(response => {
					if (!response.ok) {
						throw new Error('Errore recupero licenze padre ' + response.status + ' ' + response.statusText);
					}
					return response.json();
				})
				.then(data => {
					console.log(data);

					setFormValues('createForm', data);
				})
				.catch(error => console.error(error));
		}

    });
</script>

<?= $this->endSection() ?>