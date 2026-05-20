<?php ob_start(); ?>
<main>
    
    <?php $nojs = Configuration::get("disable_js"); ?>
    <?php if (!$nojs): ?>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search item" class="search-input"
            value="<?= $search_query ?? '' ?>">
        <i class="bi bi-search search-icon"></i>
    </div>
    <?php endif ?>

    <?php require "partials/browser_item/_items_participating.php"; ?>

    <?php require "partials/browser_item/_other_items.php"; ?>

</main>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>