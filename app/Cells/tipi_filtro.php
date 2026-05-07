<?php
/**
 * @var array  $tipi
 */
?>

<?php foreach ($tipi as $tipo): ?>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="tipi" value="<?= esc($tipo['tipo']) ?>" id="tipo_<?= esc($tipo['tipo']) ?>">
        <label class="form-check-label" for="tipo_<?= esc($tipo['tipo']) ?>">
            <?= esc($tipo['tipo']) ?>
        </label>
    </div>
<?php endforeach; ?>
