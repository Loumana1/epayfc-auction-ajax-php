<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li>
                    <?= htmlspecialchars($error) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form id="edit-form" action="edit_profile/save" method="post">
    <div class="section">
        <div class="section-header">Personal Information</div>
        <div class="section-content">
            <div class="form-group">
                <label class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" name="full_name" class="form-input"
                       value="<?= htmlspecialchars($user['full_name']) ?>" required>
                <p class="form-help">Your complete name as it should appear on your profile</p>
            </div>

            <div class="form-group">
                <label class="form-label">Username <span class="required">*</span></label>
                <input type="text" name="pseudo" class="form-input"
                       value="<?= htmlspecialchars($user['pseudo']) ?>" required>
                <p class="form-help">This will be your public display name on the platform</p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">Contact & Payment</div>
        <div class="section-content">
            <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="email" class="form-input"
                       value="<?= htmlspecialchars($user['email']) ?>" required>
                <p class="form-help">
                    We'll use this email to send you notifications about your sales and purchases
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">IBAN <span class="optional">(Optional)</span></label>
                <?php $user_iban = htmlspecialchars($user['iban'] ?? ''); ?>
                <input type="text" name="iban" class="form-input"
                       value="<?= $user_iban ?>" 
                       placeholder="BE99 9999 9999 9999 9999">
                <p class="form-help">
                    For receiving payments from your sales. Leave empty if you don't want to
                    receive payments directly.
                </p>
            </div>
        </div>
    </div>
</form>