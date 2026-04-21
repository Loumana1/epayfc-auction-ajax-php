<?php ob_start() ?>

<div class="edit-profile-page">

        <button type="submit" form="edit-form" class="save-btn"><i class="bi bi-save me-1"></i></button>

    <main>
            <?php require  "partials/edit_profile/_edit_profile.php"; ?>

    </main>

</div>
<?php $content = ob_get_clean() ?>
<?php require 'view_layout.php' ?>
<script src="js/user_validation.js"></script>