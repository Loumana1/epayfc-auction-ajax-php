<div class="card">
        <h2>Sign up</h2>

        <hr class="separator">

        <form method="post" action="signup/register">

            <div class="input-group <?= !empty($errors['email']) ? 'input-error' : '' ?>">
                <span class="icon"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
            </div>
            <?php if (!empty($errors['email'])): ?>
                <div class="field-error">
                    <?php foreach ($errors['email'] as $e): ?>
                        <?= htmlspecialchars($e) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="input-group <?= !empty($errors['full_name']) ? 'input-error' : '' ?>">
                <span class="icon"><i class="bi bi-person"></i></span>
                <input type="text" name="full_name" placeholder="Full Name" value="<?= htmlspecialchars($full_name) ?>">
            </div>
            <?php if (!empty($errors['full_name'])): ?>
                <div class="field-error">
                    <?php foreach ($errors['full_name'] as $e): ?>
                        <?= htmlspecialchars($e) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="input-group <?= !empty($errors['pseudo']) ? 'input-error' : '' ?>">
                <span class="icon"><i class="bi bi-at"></i></span>
                <input type="text" name="pseudo" placeholder="Pseudo" value="<?= htmlspecialchars($pseudo) ?>">
            </div>
            <?php if (!empty($errors['pseudo'])): ?>
                <div class="field-error">
                    <?php foreach ($errors['pseudo'] as $e): ?>
                        <?= htmlspecialchars($e) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="input-group <?= !empty($errors['password']) ? 'input-error' : '' ?>">
                <span class="icon"><i class="bi bi-key"></i></span>
                <input type="password" name="password" placeholder="Password">
            </div>
            <?php if (!empty($errors['password'])): ?>
                <div class="field-error">
                    <?php foreach ($errors['password'] as $e): ?>
                        <?= htmlspecialchars($e) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="input-group <?= !empty($errors['password_confirm']) ? 'input-error' : '' ?>">
                <span class="icon"><i class="bi bi-key-fill"></i></span>
                <input type="password" name="password_confirm" placeholder="Confirm your password">
            </div>
            <?php if (!empty($errors['password_confirm'])): ?>
                <div class="field-error">
                    <?php foreach ($errors['password_confirm'] as $e): ?>
                        <?= htmlspecialchars($e) ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <button class="btn btn-signup" type="submit">Sign Up</button>
        </form>

        <a class="btn btn-cancel" href="login">Cancel</a>
    </div>