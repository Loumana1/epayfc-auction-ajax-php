<?php ob_start(); ?>
<main>
    
    <?php $nojs = Configuration::get("disable_js"); ?>
    <?php if (!$nojs): ?>
    <?php
    $safe_categories = [];
    foreach (($categories ?? []) as $cat) {
        if (is_object($cat) && isset($cat->id) && isset($cat->name)) {
            $safe_categories[] = $cat;
        }
    }
    ?>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search item" class="search-input"
               value="<?= htmlspecialchars($initial_query ?? '', ENT_QUOTES, 'UTF-8') ?>"
               data-initial-query="<?= htmlspecialchars($initial_query ?? '', ENT_QUOTES, 'UTF-8') ?>"
               data-search-state="<?= htmlspecialchars($search_state ?? '', ENT_QUOTES, 'UTF-8') ?>"
               data-list-origin="<?= htmlspecialchars($list_origin ?? 'browser', ENT_QUOTES, 'UTF-8') ?>"
               data-initial-category="<?= (int) ($category_id ?? 0) ?>">
        <i class="bi bi-search search-icon"></i>
        <select id="category-select" class="category-select">
            <option value="0" <?= ($category_id ?? 0) == 0 ? 'selected' : '' ?>>All categories</option>
            <?php foreach ($safe_categories as $cat): ?>
                <?php
                $cat_data = is_object($cat) ? get_object_vars($cat) : [];
                $cat_id = (int) ($cat_data['id'] ?? 0);
                $cat_name = htmlspecialchars((string) ($cat_data['name'] ?? ''), ENT_QUOTES, 'UTF-8');
                ?>
                <option value="<?= $cat_id ?>" <?= ($category_id ?? 0) == $cat_id ? 'selected' : '' ?>>
                    <?= $cat_name ?>
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