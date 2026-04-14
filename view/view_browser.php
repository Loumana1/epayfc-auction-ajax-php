<?php ob_start(); ?>
<main>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search item" class="search-input"
            value="<?= htmlspecialchars($search_query ?? '') ?>">
        <i class="bi bi-search search-icon"></i>
    </div>
    <?php require "partials/browser_item/_items_participating.php"; ?>

    <?php require "partials/browser_item/_other_items.php"; ?>

</main>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>