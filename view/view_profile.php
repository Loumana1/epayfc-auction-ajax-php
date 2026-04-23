<?php ob_start() ; ?>

<div class="profile-page">

    <main>

        <?php require  "partials/profile/_profile.php"; ?>
    
    </main>
    
</div>

<?php $content = ob_get_clean() ;?>
<?php require "view_layout.php" ;?>