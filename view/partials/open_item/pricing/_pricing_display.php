<?php if ($item->get_Is_Auction()): ?>

    <div class="price-row">
        <label class="price-label">Current Bid €</label>
        <p class="price-value-current-bid">€ <?= number_format($maxBidTime ?? $minBidAmount, 2, ',', '.') ?></p>
    </div>
    
    <?php if ($item->get_Buy_Now_Price()): ?>
        <div class="price-row">
            <label class="price-label">Buy Now</label>
            <p class="price-value">€ <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?></p>
        </div>
    <?php else: ?>
        <div class="price-row">
            <label class="price-texte-small-grey">Starting Bid € <?= number_format($item->get_Starting_Bid(), 2, ',', '.') ?></label>
        </div>
    <?php endif; ?>

<?php else: ?>

    <div class="price-row">
        <label class="price-label">Price</label>
        <p class="price-value-current-bid">€ <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?></p>
    </div>
<?php endif; ?>