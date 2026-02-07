<div class="content-wrapper">
    <main class="main-content">

        <section class="page-header">
            <div class="page-header-text">
                <h2 class="page-title">Completed Sales</h2>
                <p class="page-subtitle">A snapshot of the deals you've wrapped up.</p>
            </div>
            <span class="badge badge-green">
                <?= $statistics['sales_count'] ?> sale<?= $statistics['sales_count'] > 1 ? 's' : '' ?>
            </span>
        </section>

        <section class="stats-row">
            <div class="stat-box">
                <span class="stat-label">TOTAL REVENUE</span>
                <span class="stat-value">€ <?= number_format($statistics['total_revenue'], 2, ',', '.') ?></span>
                <span class="stat-desc">Across <?= $statistics['sales_count'] ?> sales</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">AVERAGE TICKET</span>
                <span class="stat-value">€ <?= number_format($statistics['average_ticket'], 2, ',', '.') ?></span>
                <span class="stat-desc">Median buyer appetite indicator</span>
            </div>
            <div class="stat-box">
                <span class="stat-label"><strong>LOYAL BIDDER</strong></span>
                <span class="stat-value stat-value-text"><?= $statistics['loyal_bidder'] ?? 'N/A' ?></span>
                <span class="stat-desc">Most recurring winning bidder</span>
            </div>
        </section>

        <?php if (!empty($sold_items)): ?>
            <section class="sales-grid">
                <?php foreach ($sold_items as $item): ?>
                    <div class="sale-card">
                        <a href="open_item/index/<?= $item['id'] ?>" class="sale-card-link">
                            <div class="sale-card-image">
                                <?php if ($item['pic_path']): ?>
                                    <img src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg', $item['pic_path']) ?>" alt="">
                                <?php else: ?>
                                    <div class="no-pic">No Image</div>
                                <?php endif; ?>
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
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <div class="no-sales">
                <i class="bi bi-inbox"></i>
                <p>You haven't completed any sales yet.</p>
                <a href="add_edit_item" class="btn btn-place-bid">Create Your First Listing</a>
            </div>
        <?php endif; ?>

    </main>
</div>
