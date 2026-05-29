<section class="section item-details-section">
<div class="item-header-row">
        <h2><?= $item_title ?></h2>
        <div class="button-type-of-sale">
            <?php if ($is_auction): ?>
                <span class="tag auction-sale-tag">Auction</span>
            <?php else: ?>
                <span class="tag direct-sale-tag">Direct Sale</span>
            <?php endif; ?>
        </div>
    </div>
    
    <p class="item-description"><?= $item_description ?? 'No description' ?></p>
    
    <?php if (!empty($item_categories)): ?>
    <div class="item-categories" style="margin: 15px 0;">
        <strong>Categories:</strong>
        <ul style="margin-top: 5px; padding-left: 20px;">
            <?php foreach ($item_categories as $category): ?>
                <li><?= $category->name ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <div class="item-dates">
        <p>start:
            <strong>
            <?= date('d/m/Y H:i:s', strtotime($item_created_at)) ?>
             </strong>
        </p>
        <p<?= !empty($auction_ended) ? ' class="ended"' : '' ?>>Ends:<strong> 
            <?= $item_end_at ? date('d/m/Y H:i:s', strtotime($item_end_at)) : 'N/A' ?>
        </strong></p>
    </div>
</section>