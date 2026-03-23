<?php if (count($pictures) > 1): ?>
<section class="additional-images-section">
    <h3 class="section-title">Additional Images</h3>

    <div class="thumbnail-gallery">
        <?php foreach ($pictures as $index => $picture): ?>
            <a href="open_item/index/<?= $item->get_Id() ?>/<?= $index ?>">
                <img class="thumbnail <?= $selected_img === $index ? 'active' : '' ?>" 
                
                     src="<?= str_replace('.jpg', '_thumbnail.jpg', $picture->picture_path) ?>" 
                     alt="Thumbnail <?= $index + 1 ?>">
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>