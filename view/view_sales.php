<?php $sales_count = (int)($statistics['sales_count'] ?? 0); ?>
<div class="content-wrapper">
    <main class="main-content">

        <section class="page-header">
            <div class="page-header-text">
                <h2 class="page-title">Completed Sales</h2>
                <p class="page-subtitle">A snapshot of the deals you've wrapped up.</p>
            </div>
            <span class="badge badge-green">
                <?= $sales_count ?> sale<?= $sales_count > 1 ? 's' : '' ?>
            </span>
        </section>

        <section class="stats-row">
            <div class="stat-box">
                <span class="stat-label">TOTAL REVENUE</span>
                <span class="stat-value"><?= format_euro($statistics['total_revenue'] ?? 0) ?></span>
                <span class="stat-desc">Across <?= $sales_count ?> sales</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">AVERAGE TICKET</span>
                <span class="stat-value"><?= format_euro($statistics['average_ticket'] ?? 0) ?></span>
                <span class="stat-desc">Median buyer appetite indicator</span>
            </div>
            <div class="stat-box">
                <span class="stat-label"><strong>LOYAL BIDDER</strong></span>
                <span class="stat-value stat-value-text"><?= htmlspecialchars($statistics['loyal_bidder'] ?? 'N/A') ?></span>
                <span class="stat-desc">Most recurring winning bidder</span>
            </div>
        </section>

        <?php if (!empty($sold_items)): ?>
            <section class="sales-grid">
                <?php foreach ($sold_items as $item): ?>
                    <?php
                    if (!$item instanceof Item) {
                        continue;
                    }
                    $main_pic = $item->get_main_picture();
                    $pic_path = $main_pic ? $main_pic->picture_path : null;
                    $picture_count = count($item->get_pictures());
                    $display_price = $item->get_Buy_Now_Price() ?? $item->get_Starting_Bid();
                    $max_bid = $item->get_max_bid_time();
                    $winner_pseudo = $item->get_highest_bidder_pseudo();
                    $closed_at = $item->get_End_At();
                    $thumb_url = $pic_path
                        ? $web_root . str_replace('.jpg', '_thumbnail.jpg', $pic_path)
                        : '';
                    $closed_at_formatted = $closed_at ? date('d/m/Y H:i', strtotime($closed_at)) : '';
                    ?>
                    <div class="sale-card">
                     <a href="open_item/index/<?= $item->get_Id() ?>/0/sales" class="sale-card-link">
                            <div class="sale-card-image">
                                <?php if ($thumb_url): ?>
                                    <img src="<?= $thumb_url ?>" alt="">
                                <?php else: ?>
                                    <div class="no-pic">No Image</div>
                                <?php endif; ?>
                                <div class="sale-card-labels">
                                    <?php if ($item->get_Is_Auction()): ?>
                                        <span class="label label-auction"><i class="bi bi-hammer"></i> Auction</span>
                                    <?php endif; ?>
                                    <?php if ($item->get_Has_buy_now_price()): ?>
                                        <span class="label label-buy-now"><i class="bi bi-bag"></i> Buy Now</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($picture_count > 0): ?>
                                    <span class="picture-badge"><i class="bi bi-images"></i> <?= $picture_count ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="sale-card-info">
                                <h3><?= htmlspecialchars($item->get_Title()) ?></h3>
                                <p class="seller">by <?= htmlspecialchars($item->get_seller()->get_Pseudo()) ?></p>
                                <div class="pricing">
                                    <span class="price"><?= format_euro($display_price) ?></span>
                                    <?php if ($max_bid): ?>
                                        <span class="current-bid">
                                            Current bid<br>
                                            <strong><?= format_euro($max_bid) ?></strong>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="status"><i class="bi bi-clock"></i> Closed</div>
                            </div>
                        </a>
                        <div class="sale-details">
                            <div class="detail-row">
                                <i class="bi bi-currency-dollar"></i>
                                <span>Final price <?= format_euro($item->get_max_bid_time()) ?></span>
                            </div>
                            <div class="detail-row">
                                <i class="bi bi-trophy"></i>
                                <span><?= htmlspecialchars($winner_pseudo ?? 'Unknown') ?></span>
                            </div>
                            <div class="detail-row">
                                <i class="bi bi-clock-history"></i>
                                <span>Closed on <?= $closed_at_formatted ?></span>
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
