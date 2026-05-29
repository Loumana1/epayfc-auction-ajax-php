<?php ob_start(); ?>
<main>
    
    <?php $nojs = Configuration::get("disable_js"); ?>
    <?php if (!$nojs): ?>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search item" class="search-input"
                      value="<?= $initial_query ?? '' ?>"
               data-initial-query="<?= $initial_query ?? '' ?>"
               data-search-state="<?= $search_state ?? '' ?>"
               data-list-origin="<?=$list_origin ?? 'browser'?>">
        <i class="bi bi-search search-icon"></i>
        <input type="text" id="search-input" placeholder="Search items..." class="search-input"
            value="<?= htmlspecialchars($search_query ?? '') ?>">
        <select id="category-select" class="category-select">
            <option value="0" <?= ($category_id ?? 0) == 0 ? 'selected' : '' ?>>All categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat->id ?>" <?= ($category_id ?? 0) == $cat->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
       <p id="no-items-message" class="no-items-message" style="display:none;">No item found.</p>
    <?php endif ?>

    <?php require "partials/browser_item/_items_participating.php"; ?>

    <?php require "partials/browser_item/_other_items.php"; ?>

</main>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>