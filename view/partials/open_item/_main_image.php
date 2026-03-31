
    <!-- caché par dfault -->
<div id="item-carousel" class="carousel slide d-none" data-bs-ride="false" data-item-id="<?= $item_id ?>">
    <div class="carousel-inner" id="carousel-inner">
        
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#item-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#item-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<section id="static-main-image">
    <?php if (!empty($main_picture_path)): ?>
        <img src="<?= $main_picture_path ?>" 
             alt="<?= $item_title ?>"
             class="main-item-image">
    <?php else: ?>
        <div class="image-placeholder">
            <span class="placeholder-text">
                No image available for this item
            </span>
        </div>
    <?php endif; ?>
</section>