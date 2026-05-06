<select name="tipilicenze_id" id="tipilicenze_id" class="form-select" required>
    <option value="">-- Seleziona --</option>
    <?php foreach ($gruppi as $nomeTipo => $opzioni): ?>
    <optgroup label="<?= esc($nomeTipo) ?>">
        <?php foreach ($opzioni as $t): ?>
        <option value="<?= esc($t['id']) ?>"
                data-tipo="<?= esc($t['tipo']) ?>"
                data-opt-categoria="<?= esc($t['categoria']) ?>"
                <?= $selezionato == $t['id'] ? 'selected' : '' ?>>
            <?= esc($t['tipo']) .' - '.esc($t['modello'] ?: $nomeTipo) ?>
        </option>
        <?php endforeach; ?>
    </optgroup>
    <?php endforeach; ?>
</select>
