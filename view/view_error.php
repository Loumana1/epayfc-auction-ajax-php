<?php ob_start(); ?>

<?php

$no_header_footer = true;

$error_back_url = 'browser';
?>
<main class="error-container">
    <div class="error-icon-wrapper">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>
    <h1 class="error-oops">ERROR!</h1>
    <div class="error-box">
        <div class="error-details-box">
            <?= $error ?? '' ?>
        </div>
    </div>
</main>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>
