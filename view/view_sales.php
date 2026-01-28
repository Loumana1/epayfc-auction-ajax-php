<?php
require_once "utils/AppTime.php";
require_once "framework/Configuration.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sales</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">   <!-- PAS browser.css -->
    <link rel="stylesheet" href="css/sales.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<header>
    <div class="header-content">
        <a href="profile" class="header-back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-title">
            <span>Sales</span>
            <i class="bi bi-cart-fill"></i>
        </h1>
        <div class="header-spacer"></div>
    </div>
</header>

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
                <span class="stat-label">LOYAL BIDDER</span>
                <span class="stat-value"><?= $statistics['loyal_bidder'] ?? 'N/A' ?></span>
                <span class="stat-desc">Most recurring winning bidder</span>
            </div>
        </section>

        
        <?php if (!empty($sold_items)): ?>
            <section class="sales-grid">
                <?php foreach ($sold_items as $item): ?>
                    <?php include "view/partials/sales/_sale_card.php"; ?>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <?php include "view/partials/sales/_no_sales.php"; ?>
        <?php endif; ?>

    </main>
</div>

<?php include "view/partials/_navbar.php"; ?>
<?php include "view/partials/_timebar.php"; ?>

</body>
</html>