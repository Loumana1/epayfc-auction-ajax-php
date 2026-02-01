<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Purchases</title>
    <base href="<?= $web_root ?>">
    <link rel="stylesheet" href="css/purchases.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>

<div class="purchases-page">

    <header class="page-header">
        <a href="browser/index" class="back">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1>My Purchases 🛍️</h1>
    </header>

    <?php
    function euro(float $v): string {
        return "€ " . number_format($v, 2, ",", " ");
    }
    ?>

    <!-- STATS -->
    <section class="stats">
        <div class="stat-card">
            <span class="label">Total spent</span>
            <span class="value"><?= euro($stats["total"]) ?></span>
            <span class="sub">Across <?= $stats["count"] ?> purchases</span>
        </div>

        <div class="stat-card">
            <span class="label">Average ticket</span>
            <span class="value"><?= euro($stats["average"]) ?></span>
            <span class="sub">Helps you plan future bids</span>
        </div>

        <div class="stat-card">
            <span class="label">Top seller</span>
            <span class="value"><?= $stats["top_seller"] ?? "—" ?></span>
            <span class="sub">Who you've bought from the most</span>
        </div>
    </section>

    <!-- PURCHASES -->
    <?php if (!empty($items)): ?>
        <section class="items-section">
            <div class="items-grid">

                <?php foreach ($items as $item): ?>

                    <?php
                    $pic = ItemPicture::get_main_picture($item->get_id());
                    $img = $pic ? $pic->picture_path : "assets/no-image.png";
                    ?>

                    <div class="item-card closed">

                        <div class="image-wrapper">
                            <img src="<?= $img ?>" alt="">

                            <span class="images-count">
                                <i class="bi bi-images"></i>
                                <?= count($item->get_pictures()) ?> images
                            </span>

                            <span class="badge success">
                                <i class="bi bi-trophy"></i> Highest Bidder
                            </span>
                        </div>

                        <div class="item-body">
                            <h3><?= $item->get_title() ?></h3>
                            <p class="seller">by <?= $item->get_seller()->get_pseudo() ?></p>

                            <div class="price">
                                <span class="main"><?= euro($item->get_max_bid_time()) ?></span>
                                <span class="sub">Paid</span>
                            </div>

                            <div class="time closed">
                                <i class="bi bi-lock"></i>
                                Closed
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>
        </section>
    <?php endif; ?>

</div>

</body>
</html>