<?php if ($is_auction): ?>

    <div class="price-row">
        <label class="price-label">Current Bid €</label>
        <p class="price-value-current-bid">
            <?= format_euro($max_bid_time ?? $min_bid_amount) ?>
        </p>
    </div>
    
    <?php if ($buy_now_price): ?>
        <div class="price-row">
            <label class="price-label">Buy Now</label>
            <p class="price-value">
                <?= format_euro($buy_now_price) ?>
            </p>
        </div>
    <?php else: ?>
        <div class="price-row">
            <label class="price-texte-small-grey">
                Starting Bid <?= format_euro($starting_bid) ?>
            </label>
        </div>
    <?php endif; ?>

<?php else: ?>

    <div class="price-row">
        <label class="price-label">Price</label>
        <p class="price-value-current-bid">
            <?= format_euro($buy_now_price) ?>
        </p>
    </div>
<?php endif; ?>