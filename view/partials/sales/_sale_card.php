<div class="sale-card">
    <a href="open_item/index/<?= $item['id'] ?>" class="sale-card-link">
        
        <!-- Image -->
        <div class="sale-card-image">
            <?php if ($item['pic_path']): ?>
                <img src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg', $item['pic_path']) ?>" alt="">
            <?php else: ?>
                <div class="no-pic">No Image</div>
            <?php endif; ?>
            
            <!-- Labels -->
            <div class="sale-card-labels">
                <?php if ($item['is_auction']): ?>
                    <span class="label label-auction"><i class="bi bi-hammer"></i> Auction</span>
                <?php endif; ?>
                <?php if ($item['has_buy_now']): ?>
                    <span class="label label-buy-now"><i class="bi bi-bag"></i> Buy Now</span>
                <?php endif; ?>
            </div>
            
            <?php if ($item['picture_count'] > 0): ?>
                <span class="picture-badge"><i class="bi bi-images"></i> <?= $item['picture_count'] ?></span>
            <?php endif; ?>
        </div>
        
        <!-- Info -->
        <div class="sale-card-info">
            <h3><?= htmlspecialchars($item['title']) ?></h3>
            <p class="seller">by <?= htmlspecialchars($item['seller_pseudo']) ?></p>
            
            <div class="pricing">
                <span class="price">€ <?= number_format($item['buy_now_price'] ?? $item['starting_bid'], 2, ',', '.') ?></span>
                <?php if ($item['max_bid']): ?>
                    <span class="current-bid">
                        Current bid<br>
                        <strong>€ <?= number_format($item['max_bid'], 2, ',', '.') ?></strong>
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="status"><i class="bi bi-clock"></i> Closed</div>
        </div>
    </a>
    
    <!-- Détails vente -->
    <div class="sale-details">
        <div class="detail-row">
            <i class="bi bi-currency-dollar"></i>
            <span>Final price € <?= number_format($item['final_price'], 2, ',', '.') ?></span>
        </div>
        <div class="detail-row">
            <i class="bi bi-trophy"></i>
            <span><?= htmlspecialchars($item['winner_pseudo'] ?? 'Unknown') ?></span>
        </div>
        <div class="detail-row">
            <i class="bi bi-clock-history"></i>
            <span>Closed on <?= date('d/m/Y H:i', strtotime($item['closed_at'])) ?></span>
        </div>
    </div>
</div>