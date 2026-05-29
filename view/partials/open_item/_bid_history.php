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
                        <span class="bidder-name"><?= $bid->get_pseudo() ?></span>
                        <span class="bid-date">
                            <?= date('d/m/Y H:i:s', strtotime($bid->get_created_at())) ?>
                        </span>
                    </div>
                    <span class="bid-amount">
                        <?= format_euro($bid->get_amount()) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No bids yet. Be the first to participate!</p>
    <?php endif; ?>
</section>