<div class="card">
    <h2>Sign up</h2>

    <hr class="separator">

    <form method="post" action="signup/register">

        <?php $email_err = !empty($errors['email']) ? 'input-error' : ''; ?>
        <div class="input-group <?= $email_err ?>">
            <span class="icon"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" placeholder="Email" 
                   value="<?= $email ?>">
        </div>
        <?php if (!empty($errors['email'])): ?>
            <div class="field-error">
                <?php foreach ($errors['email'] as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $fn_err = !empty($errors['full_name']) ? 'input-error' : ''; ?>
        <div class="input-group <?= $fn_err ?>">
            <span class="icon"><i class="bi bi-person"></i></span>
            <input type="text" name="full_name" placeholder="Full Name" 
                   value="<?= $full_name ?>">
        </div>
        <?php if (!empty($errors['full_name'])): ?>
            <div class="field-error">
                <?php foreach ($errors['full_name'] as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $pseudo_err = !empty($errors['pseudo']) ? 'input-error' : ''; ?>
        <div class="input-group <?= $pseudo_err ?>">
            <span class="icon"><i class="bi bi-at"></i></span>
            <input type="text" name="pseudo" placeholder="Pseudo" 
                   value="<?= $pseudo ?>">
        </div>
        <?php if (!empty($errors['pseudo'])): ?>
            <div class="field-error">
                <?php foreach ($errors['pseudo'] as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $pass_err = !empty($errors['password']) ? 'input-error' : ''; ?>
        <div class="input-group <?= $pass_err ?>">
            <span class="icon"><i class="bi bi-key"></i></span>
            <input type="password" name="password" placeholder="Password">
        </div>
        <?php if (!empty($errors['password'])): ?>
            <div class="field-error">
                <?php foreach ($errors['password'] as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $conf_err = !empty($errors['password_confirm']) ? 'input-error' : ''; ?>
        <div class="input-group <?= $conf_err ?>">
            <span class="icon"><i class="bi bi-key-fill"></i></span>
            <input type="password" name="password_confirm" placeholder="Confirm your password">
        </div>
        <?php if (!empty($errors['password_confirm'])): ?>
            <div class="field-error">
                <?php foreach ($errors['password_confirm'] as $e): ?>
                    <?= $e ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <button class="btn btn-signup" type="submit">Sign Up</button>
    </form>

    <a class="btn btn-cancel" href="login">Cancel</a>
</div>