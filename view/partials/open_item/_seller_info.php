<?php if ($seller): ?>
<section class="section seller-section">
    <h3 class="section-title-small">Seller Information</h3>
    <div class="seller-info">
        <?php if ($seller->has_Picture()): ?>
            <img src="<?= $seller->get_Thumbnail_Path() ?>" class="seller-pic">
        <?php else: ?>
            <div class="seller-pic placeholder"></div>
        <?php endif; ?>
        
        <div class="seller-details">
            <h2 class="seller-name"><?= $seller->get_Pseudo() ?></h2>
            <p class="price-texte-small-grey">Member</p>
        </div>
    </div>
</section>
<?php endif; ?>