
<?php ob_start(); ?>

<?php $form_id = 'change-password-form'; ?>
<div class="content-wrapper">
    <main class="main-content change-password-container">

        <form id="<?= $form_id ?>" method="post" action="user/change_password" class="change-password-form" novalidate>

            <section class="password-section">
                <div class="password-section-header">
                    <h3 class="password-section-title">Current Password</h3>
                </div>
                
                <div class="password-section-content">
                    <div class="form-group">
                        <label for="current_password">Current Password <span class="required">*</span></label>
                        <input type="password"
                                id="current_password"
                                name="current_password"
                                placeholder="Enter your current password"
                                autocomplete="current-password"
                                class="<?= !empty($field_errors_current_password) ? 'input-error' : '' ?>">
                        <?php if (!empty($field_errors_current_password)): ?>
                            <?php foreach ($field_errors_current_password as $error): ?>
                                <span class="field-error"><?= $error ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <small class="form-hint">
                            Enter your current password to verify your identity.
                        </small>
                    </div>
                </div>
            </section>

            <section class="password-section">
                <div class="password-section-header">
                    <h3 class="password-section-title">New Password</h3>
                </div>
                <div class="password-section-content">
                    <div class="form-group">
                        <label for="new_password">New Password <span class="required">*</span></label>
                        <input type="password"
                                id="new_password"
                                name="new_password"
                                placeholder="Enter your new password"
                                autocomplete="new-password"
                                class="<?= !empty($field_errors_new_password) ? 'input-error' : '' ?>">
                        <?php if (!empty($field_errors_new_password)): ?>
                            <?php foreach ($field_errors_new_password as $error): ?>
                                <span class="field-error"><?= $error ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <small class="form-hint">
                            Password must be 8-16 characters with uppercase, number, and punctuation.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password <span class="required">*</span></label>
                        <input type="password"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Confirm your new password"
                                autocomplete="new-password"
                                class="<?= !empty($field_errors_confirm_password) ? 'input-error' : '' ?>">
                        <?php if (!empty($field_errors_confirm_password)): ?>
                            <?php foreach ($field_errors_confirm_password as $error): ?>
                                <span class="field-error"><?= $error ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <small class="form-hint">
                            Re-enter your new password to confirm it matches.
                        </small>
                    </div>
                </div>
            </section>

        </form>

    </main>
</div>
<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>