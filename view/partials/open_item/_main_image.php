<?php
$mainPicturePath = null;
if (!empty($pictures)) {
    $mainPicturePath = $pictures[$selectedImg]['picture_path'] ?? $pictures[0]['picture_path'];
}
?>

<section>
    <?php if ($mainPicturePath): ?>
        <img src="<?= $mainPicturePath ?>" 
             alt="<?= htmlspecialchars($item->get_Title()) ?>"
             class="main-item-image">
    <?php else: ?>
        <div class="image-placeholder">
            <span class="placeholder-text">No image available for this item</span>
        </div>
    <?php endif; ?>
</section>