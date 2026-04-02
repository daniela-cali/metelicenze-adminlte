<?php
/**
 * Partial riusabile: form di login Shield.
 * Incluso sia dalla pagina /login dedicata sia dal modal nella home.
 * Non contiene layout né extend — solo il markup del form.
 */
?>

<!-- Messaggio di errore singolo restituito da Shield -->
<?php if (session('error') !== null) : ?>
    <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
<?php elseif (session('errors') !== null) : ?>
    <!-- Lista di errori di validazione -->
    <div class="alert alert-danger" role="alert">
        <?php if (is_array(session('errors'))) : ?>
            <?php foreach (session('errors') as $error) : ?>
                <?= esc($error) ?><br>
            <?php endforeach ?>
        <?php else : ?>
            <?= esc(session('errors')) ?>
        <?php endif ?>
    </div>
<?php endif ?>

<!-- Messaggio di successo (es. dopo magic link) -->
<?php if (session('message') !== null) : ?>
    <div class="alert alert-success" role="alert"><?= esc(session('message')) ?></div>
<?php endif ?>

<!-- Form che posta all'endpoint Shield /login -->
<form action="<?= url_to('login') ?>" method="post">
    <?= csrf_field() ?>

    <!-- Username -->
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingUsernameInput"
               name="username" inputmode="username" autocomplete="username"
               placeholder="<?= lang('Auth.username') ?>"
               value="<?= old('username') ?>" required>
        <label for="floatingUsernameInput"><?= lang('Auth.username') ?></label>
    </div>

    <!-- Password -->
    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="floatingPasswordInput"
               name="password" inputmode="text" autocomplete="current-password"
               placeholder="<?= lang('Auth.password') ?>" required>
        <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
    </div>

    <!-- Ricordami (solo se abilitato nella config Shield) -->
    <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
        <div class="form-check mb-3">
            <label class="form-check-label">
                <input type="checkbox" name="remember" class="form-check-input"
                       <?php if (old('remember')): ?> checked<?php endif ?>>
                <?= lang('Auth.rememberMe') ?>
            </label>
        </div>
    <?php endif; ?>

    <!-- Pulsante submit -->
    <div class="d-grid col-12 col-md-8 mx-auto m-3">
        <button type="submit" class="btn btn-dark btn-block"><?= lang('Auth.login') ?></button>
    </div>

    <!-- Link magic link (se abilitato) -->
    <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
        <p class="text-center">
            <?= lang('Auth.forgotPassword') ?>
            <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a>
        </p>
    <?php endif ?>

    <!-- Link registrazione (se abilitata) -->
    <?php if (setting('Auth.allowRegistration')) : ?>
        <p class="text-center">
            <?= lang('Auth.needAccount') ?>
            <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a>
        </p>
    <?php endif ?>

</form>
