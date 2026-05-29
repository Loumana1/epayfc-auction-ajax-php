<?php ob_start(); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.min.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<div class="manage-page" data-item-id="<?= $item->get_Id() ?>"
data-search-state="<?= $search_state ?? '' ?>">

    <main>
        <h1 class="page-subtitle">
            Manage Images for "<?= $item->get_Title() ?>"
        </h1>

        <?php include __DIR__ . "/partials/manage_images/_manage_images.php"; ?>
    </main>
        
</div>
<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>

    
    