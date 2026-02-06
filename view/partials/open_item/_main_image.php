<section>
    <?php if (!empty($main_picture_path)): ?>
        <img src="<?= $main_picture_path ?>" 
             alt="<?= htmlspecialchars($item->get_Title()) ?>"
             class="main-item-image">
    <?php else: ?>
        <div class="image-placeholder">
            <span class="placeholder-text">No image available for this item</span>
        </div>
    <?php endif; ?>
</section>