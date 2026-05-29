<?php ob_start(); ?>

<div class="top-title">
    <i class="bi bi-cart4"></i> EPayFC
</div>

<div class="card">
    <h2>Sign in</h2>

    <hr class="separator">

    <form method="post" action="login/login">

        <div class="input-group <?= !empty($errors['mail']) ? 'input-error' : '' ?>">
            <span class="icon"><i class="bi bi-person"></i></span>
            <input type="text"
                   name="mail"
                   placeholder="Mail"
                   value="<?= $mail ?>">
        </div>
        <?php if (!empty($errors['mail'])): ?>
            <div class="field-error">
                <?php foreach ($errors['mail'] as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="input-group <?= !empty($errors['password']) ? 'input-error' : '' ?>">
            <span class="icon"><i class="bi bi-key"></i></span>
            <input type="password"
                   name="password"
                   placeholder="Password">
        </div>
        <?php if (!empty($errors['password'])): ?>
            <div class="field-error">
                <?php foreach ($errors['password'] as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button class="btn btn-login" type="submit">Login</button>
    </form>

    <a class="btn btn-guest" href="browser">Continue as guest</a>

    <p class="subscribe">
        <a href="signup">New here ? Click here to subscribe !</a>
    </p>

    <hr class="separator">

    <?php if (Configuration::is_dev()): ?>
        <div class="debug">
            <div class="debug-title">For Debug Purpose</div>

            <?php foreach ($dev_users as $u): ?>
                <a href="login/login_as/<?= $u->id ?>">Login as <?= $u->get_email() ?></a><br>
            <?php endforeach; ?>

            <a href="setup/install" class="debug-action debug-ok">
                Restore original data
            </a>

            <a href="setup/export" class="debug-action debug-warn">
                Backup personal data
            </a>

            <a href="setup/restore" class="debug-action debug-warn">
                Restore personal data
            </a>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>
