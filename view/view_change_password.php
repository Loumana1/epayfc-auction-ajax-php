<?php
require_once "framework/Configuration.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/change_password.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php 
    $form_id = 'change-password-form';
    include __DIR__ . "/partials/_header.php"; 
    ?>
    
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
                                    class="<?= !empty($fieldErrors['current_password']) ? 'input-error' : '' ?>">
                            <?php if (!empty($fieldErrors['current_password'])): ?>
                                <?php 
                                $fieldErrorsToShow = $fieldErrors['current_password'];
                                include __DIR__ . "/partials/_field_error.php"; 
                                ?>
                            <?php endif; ?>
                            <small class="form-hint">
                                Enter your current password to verify your identity.
                            </small>
                        </div>
                    </div>
                </section>
                
                <!--  New Password -->
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
                                    class="<?= !empty($fieldErrors['new_password']) ? 'input-error' : '' ?>">
                            <?php if (!empty($fieldErrors['new_password'])): ?>
                                <?php 
                                $fieldErrorsToShow = $fieldErrors['new_password'];
                                include __DIR__ . "/partials/_field_error.php"; 
                                ?>
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
                                        class="<?= !empty($fieldErrors['confirm_password']) ? 'input-error' : '' ?>">
                            <?php if (!empty($fieldErrors['confirm_password'])): ?>
                                <?php 
                                $fieldErrorsToShow = $fieldErrors['confirm_password'];
                                include __DIR__ . "/partials/_field_error.php"; 
                                ?>
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
    
    <?php include __DIR__ . "/partials/_navbar.php"; ?>
    <?php include __DIR__ . "/partials/_timebar.php"; ?>
</body>
</html>