<div class="content-wrapper">
    <main class="main-content">

        <section>
            <?php if (!empty($main_picture_path)): ?>
                <img src="<?= $main_picture_path ?>"
                     alt="<?= htmlspecialchars($item->get_Title()) ?>"
                     class="main-item-image">
            <?php else: ?>
                <div class="image-placeholder">
                    <span class="placeholder-text">No image available for this item</span>
                </div>
            <?php endif; ?>
        </section>

        <section class="section item-details-section">
            <div class="item-header-row">
                <h2><?= $item->get_Title() ?></h2>
                <div class="button-type-of-sale">
                    <?php if ($item->get_Is_Auction()): ?>
                        <span class="tag auction-sale-tag">Auction</span>
                    <?php endif; ?>
                    <?php if ($item->get_Is_Direct_Sale()): ?>
                        <span class="tag direct-sale-tag">Direct Sale</span>
                    <?php endif; ?>
                </div>
            </div>
            <p class="item-description"><?= $item->get_Description() ?? 'No description' ?></p>
            <div class="item-dates">
                <p>start:<strong><?= date('d/m/Y H:i:s', strtotime($item->get_Created_At())) ?></strong></p>
                <p <?= !empty($auction_ended) ? ' class="ended"' : '' ?>>Ends:<strong> <?= $item->get_End_At() ? date('d/m/Y H:i:s', strtotime($item->get_End_At())) : 'N/A' ?></strong></p>
            </div>
        </section>

        <?php if (count($pictures) > 1): ?>
        <section class="additional-images-section">
            <h3 class="section-title">Additional Images</h3>
            <div class="thumbnail-gallery">
                <?php foreach ($pictures as $index => $picture): ?>
                    <a href="open_item/index/<?= $item->get_Id() ?>/<?= $index ?>">
                        <img class="thumbnail <?= $selected_img === $index ? 'active' : '' ?>"
                             src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg', $picture['picture_path']) ?>"
                             alt="Thumbnail <?= $index + 1 ?>">
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($show_bid_history)): ?>
        <section class="section bid-history-section">
            <div class="bid-section-header">
                <h3 class="section-title-small">Bid History</h3>
                <?php if (!empty($bids)): ?>
                    <span class="bid-count-tag"><?= count($bids) ?> entries</span>
                <?php endif; ?>
            </div>
            <?php if (!empty($bids)): ?>
                <div class="bid-list">
                    <?php foreach ($bids as $bid): ?>
                        <div class="bid-row">
                            <div class="bid-info">
                                <span class="bidder-name"><?= $bid['pseudo'] ?></span>
                                <span class="bid-date"><?= date('d/m/Y H:i:s', strtotime($bid['created_at'])) ?></span>
                            </div>
                            <span class="bid-amount">€ <?= number_format($bid['amount'], 2, ',', '.') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No bids yet. Be the first to participate!</p>
            <?php endif; ?>
        </section>
        <?php endif; ?>

    </main>

    <aside class="sidebar-content">
        <section class="section pricing-section">
            <h3 class="section-title-small pricing-title">Pricing</h3>
            <?php if ($item->get_Is_Auction()): ?>
                <div class="price-row">
                    <label class="price-label">Current Bid €</label>
                    <p class="price-value-current-bid">€ <?= number_format($max_bid_time ?? $min_bid_amount, 2, ',', '.') ?></p>
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
            <hr class="divider-line">
            <?php if ($show_buttons): ?>
                <?php if ($item->get_Is_Auction()): ?>
                <form class="bid-form" method="post" action="bid/create">
                    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
                    <div class="bid-input-group">
                        <span>€</span>
                        <input type="number" name="amount" step="0.01"
                               min="<?= $min_bid_amount ?>" value="<?= $min_bid_amount ?>"
                               required <?= $buttons_disabled ? 'disabled' : '' ?>>
                    </div>
                    <button type="submit" class="btn-place-bid" <?= $buttons_disabled ? 'disabled' : '' ?>>
                        Place Bid
                    </button>
                </form>
                <?php endif; ?>
                <?php if ($item->get_Has_buy_now_price()): ?>
                <form method="post" action="bid/create">
                    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
                    <input type="hidden" name="amount" value="<?= $item->get_Buy_Now_Price() ?>">
                    <?php
                    $is_direct_sale_only = $item->get_Is_Direct_Sale() && !$item->get_Is_Auction();
                    $btn_class = $is_direct_sale_only ? 'btn-place-bid' : 'btn-buy-now';
                    $btn_text = $is_direct_sale_only ? 'BUY NOW' : 'Buy Now at € ' . number_format($item->get_Buy_Now_Price(), 2, ',', '.');
                    ?>
                    <button type="submit" class="<?= $btn_class ?>" <?= $buttons_disabled ? 'disabled' : '' ?>>
                        <?= $btn_text ?>
                    </button>
                </form>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (!empty($status_message)): ?>
                <p class="price-texte-small-grey"><?= $status_message ?></p>
            <?php endif; ?>
        </section>

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

        <?php if ($is_owner): ?>
        <div class="owner-section">
            <h4 class="owner-section-title">Manage Your Item</h4>
            <div class="owner-actions">
                <?php if ($has_active_bids || !$is_open): ?>
                    <span class="btn-manage-item btn-disabled">
                        <i class="bi bi-pencil"></i> Edit Item Details</span>
                <?php else: ?>
                    <a href="add_edit_item?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
                        <i class="bi bi-pencil"></i> Edit Item Details
                    </a>
                <?php endif; ?>
                <?php if (!$is_open): ?>
                    <span class="btn-manage-item btn-disabled">
                        <i class="bi bi-images"></i> Manage Images</span>
                <?php else: ?>
                    <a href="manage_images?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
                        <i class="bi bi-images"></i> Manage Images
                    </a>
                <?php endif; ?>
                <?php if (!$is_open): ?>
                    <span class="btn-delete-item btn-disabled">
                        <i class="bi bi-trash"></i> Delete Item</span>
                <?php else: ?>
                    <a href="delete_confirm/index/<?= $item->get_Id() ?>" class="btn-delete-item">
                        <i class="bi bi-trash"></i> Delete Item
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </aside>
</div>
