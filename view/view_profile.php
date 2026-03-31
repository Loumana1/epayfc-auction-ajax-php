<?php ob_start() ; ?>

<body class="profile-page">

    <main>

        <?php require  "partials/profile/_profile.php"; ?>
    
    </main>
    
</body>

<?php $content = ob_get_clean() ;?>
<?php require "view_layout.php" ;?>