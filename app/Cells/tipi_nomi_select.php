<select name="tipo" id="tipo" class="form-select" required>
    <option value="">-- Seleziona tipo --</option>
    <?php foreach ($tipi as $t): ?>
    <option value="<?= esc($t['tipo']) ?>" <?= $selezionato === $t['tipo'] ? 'selected' : '' ?>>
        <?= esc($t['tipo']) ?>
    </option>
    <?php endforeach; ?>
</select>
