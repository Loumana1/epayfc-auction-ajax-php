<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My items</title>
    <base href="<?= $web_root ?>">
    <link rel="stylesheet" href="css/my_items.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>

<div class="my-items-page">

    <header class="page-header">
        <h1>My items 🛒</h1>
    </header>

    <?php
    function euro(float $v): string {
        return "€ " . number_format($v, 2, ",", " ");
    }

    function time_left(?string $endAt): string {
        if (!$endAt) return "";
        $now = new DateTime(AppTime::get_current_datetime());
        $end = new DateTime($endAt);
        if ($end <= $now) return "Ended";

        $diff = $now->diff($end);
        if ($diff->days > 0) return "{$diff->days}d {$diff->h}h left";
        if ($diff->h > 0) return "{$diff->h}h {$diff->i}m left";
        return "{$diff->i}m left";
    }
    ?>

    <!-- ACTIVE ITEMS -->
    <?php if (!empty($active_items)): ?>
        <section class="items-section">
            <h2>Active Items</h2>

            <div class="items-grid">
                <?php foreach ($active_items as $item): ?>

                    <?php
                    $pic = ItemPicture::get_main_picture($item->get_id());
                    $img = $pic ? $pic->picture_path : "assets/no-image.png";
                    ?>

                    <div class="item-card">

                        <div class="image-wrapper">
                            <img src="<?= $img ?>" alt="">

                            <span class="images-count">
                                <i class="bi bi-images"></i>
                                <?= count($item->get_pictures()) ?> images
                            </span>

                            <?php if ($item->get_is_auction()): ?>
                                <span class="badge auction">Auction</span>
                            <?php endif; ?>

                            <?php if ($item->get_has_buy_now_price()): ?>
                                <span class="badge buy-now">Buy Now</span>
                            <?php endif; ?>
                        </div>

                        <div class="item-body">
                            <h3><?= $item->get_title() ?></h3>
                            <p class="seller">by <?= $item->get_seller()->get_pseudo() ?></p>

                            <div class="price">
                                <?php if ($item->get_max_bid_time()): ?>
                                    <span class="main"><?= euro($item->get_max_bid_time()) ?></span>
                                    <span class="sub">Current bid</span>
                                <?php else: ?>
                                    <span class="main"><?= euro($item->get_buy_now_price() ?? $item->get_starting_bid()) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="time">
                                <i class="bi bi-clock"></i>
                                <?= time_left($item->get_end_at()) ?>
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
