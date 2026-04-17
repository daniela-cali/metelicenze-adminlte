<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5" style="max-width: 500px;">
    <h3>Cambia Password</h3>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= session('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= url_to('UsersController::changePassword') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="current_password" class="form-label">Password attuale</label>
            <input type="password" class="form-control" name="current_password" id="current_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Nuova password</label>
            <input type="password" class="form-control" name="new_password" id="new_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password_confirm" class="form-label">Conferma nuova password</label>
            <input type="password" class="form-control" name="new_password_confirm" id="new_password_confirm" required>
        </div>

        <button type="submit" class="btn btn-primary">Aggiorna Password</button>
    </form>
</div>

<?= $this->endSection() ?>
