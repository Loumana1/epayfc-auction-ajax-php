
<section class="stats-row">
    <div class="stat-box">
        <span class="stat-label">TOTAL REVENUE</span>
        <span class="stat-value"><?= format_euro($statistics['total_revenue'] ?? 0) ?></span>
        <span class="stat-desc">Across <?= $sales_count ?> sale<?= $sales_count !== 1 ? 's' : '' ?></span>
    </div>
    <div class="stat-box">
        <span class="stat-label">AVERAGE TICKET</span>
        <span class="stat-value"><?= format_euro($statistics['average_ticket'] ?? 0) ?></span>
        <span class="stat-desc">Median buyer appetite indicator</span>
    </div>
    <div class="stat-box">
        <span class="stat-label"><strong>LOYAL BIDDER</strong></span>
        <span class="stat-value stat-value-text"><?= $statistics['loyal_bidder'] ?? 'N/A' ?></span>
        <span class="stat-desc">Most recurring winning bidder</span>
    </div>
</section>