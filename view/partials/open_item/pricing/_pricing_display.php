<?php if ($item->get_Is_Auction()): ?>

    <div class="price-row">
        <label class="price-label">Current Bid €</label>
        <p class="price-value-current-bid">
            <?= format_euro($max_bid_time ?? $min_bid_amount) ?>
        </p>
    </div>
    
    <?php if ($item->get_Buy_Now_Price()): ?>
        <div class="price-row">
            <label class="price-label">Buy Now</label>
            <p class="price-value">
                <?= format_euro($item->get_Buy_Now_Price()) ?>
            </p>
        </div>
    <?php else: ?>
        <div class="price-row">
            <label class="price-texte-small-grey">
                Starting Bid <?= format_euro($item->get_Starting_Bid()) ?>
            </label>
        </div>
    <?php endif; ?>

<?php else: ?>

    <div class="price-row">
        <label class="price-label">Price</label>
        <p class="price-value-current-bid">
            <?= format_euro($item->get_Buy_Now_Price()) ?>
        </p>
    </div>
<?php endif; ?>