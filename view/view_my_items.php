<?php
require_once "model/ItemPicture.php";
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My items</title>
    <base href="<?= $web_root ?>">
    <link rel="stylesheet" href="css/my_items.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="js/search_filter.js"></script>
</head>

<body>

    <div class="my-items-page">

        <?php require_once "view/partials/_header.php"; ?>


        <?php
        function euro(float $v): string
        {
            return "€ " . number_format($v, 2, ",", " ");
        }

        function time_left(?string $endAt): string
        {
            if (!$endAt)
                return "";
            $now = new DateTime(AppTime::get_current_datetime());
            $end = new DateTime($endAt);
            if ($end <= $now)
                return "Ended";

            $diff = $now->diff($end);
            if ($diff->days > 0)
                return "{$diff->days}d {$diff->h}h left";
            if ($diff->h > 0)
                return "{$diff->h}h {$diff->i}m left";
            return "{$diff->i}m left";
        }
        ?>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search item" class="search-input"
                value="<?= htmlspecialchars($search_query) ?>">
            <i class="bi bi-search search-icon"></i>
        </div>

        <!-- ACTIVE ITEMS -->
        <?php if (!empty($active_items)): ?>
            <section class="items-section">
                <h2>Active Items</h2>

                <div class="items-grid" id="active-items-container">
                    <?php foreach ($active_items as $item): ?>
                        <?php
                        $pic = ItemPicture::get_main_picture($item->get_id());
                        $img = $pic ? $pic->picture_path : "assets/no-image.png";
                        ?>
                        <div class="item-card" data-id="<?= $item->get_id() ?>"
                            onclick="window.location='open_item/index/<?= $item->get_id() ?>/0/<?= $encoded_state ?>'"
                            data-title="<?= htmlspecialchars($item->get_title() ?? '') ?>"
                            data-seller="<?= htmlspecialchars($item->get_seller()->get_Pseudo() ?? '') ?>"
                            data-description="<?= htmlspecialchars($item->get_Description() ?? '') ?>">


                            <div class="image-wrapper">
                                <img src="<?= $img ?>" alt="">
                                <span class="images-count">
                                    <i class="bi bi-images"></i>
                                    <?= count(ItemPicture::get_all_by_item($item->get_Id())) ?> images
                                </span>
                            </div>

                            <div class="item-body">
                                <h3><?= $item->get_title() ?></h3>

                                <div class="price">
                                    <span class="main">
                                        <?= euro($item->get_max_bid_time() ?? $item->get_starting_bid()) ?>
                                    </span>
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

        <!-- CLOSED UNSOLD -->
        <?php if (!empty($closed_unsold_items)): ?>
            <section class="items-section">
                <h2>Closed · Unsold Items</h2>

                <div class="items-grid" id="active-items-container">
                    <?php foreach ($closed_unsold_items as $item): ?>
                        <?php
                        $pic = ItemPicture::get_main_picture($item->get_id());
                        $img = $pic ? $pic->picture_path : "assets/no-image.png";
                        ?>
                        <div class="item-card closed" data-id="<?= $item->get_id() ?>"
                            onclick="window.location='open_item/index/<?= $item->get_id() ?>/0/<?= $encoded_state ?>'">

                            <div class="image-wrapper">
                                <img src="<?= $img ?>" alt="">
                            </div>

                            <div class="item-body">
                                <h3><?= $item->get_title() ?></h3>

                                <div class="time ended">
                                    <i class="bi bi-x-circle"></i>
                                    Not sold
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- SOLD ITEMS -->
        <?php if (!empty($sold_items)): ?>
            <section class="items-section">
                <h2>Sold Items</h2>

                <div class="items-grid" id="active-items-container">
                    <?php foreach ($sold_items as $item): ?>
                        <?php
                        $pic = ItemPicture::get_main_picture($item->get_id());
                        $img = $pic ? $pic->picture_path : "assets/no-image.png";
                        ?>
                        <div class="item-card sold" data-id="<?= $item->get_id() ?>"
                            onclick="window.location='open_item/index/<?= $item->get_id() ?>/0/<?= $encoded_state ?>'">

                            <div class="image-wrapper">
                                <img src="<?= $img ?>" alt="">
                            </div>

                            <div class="item-body">
                                <h3><?= $item->get_title() ?></h3>

                                <div class="price">
                                    <span class="main">
                                        <?= euro($item->get_max_bid_time()) ?>
                                    </span>
                                    <span class="sub">Final price</span>
                                </div>

                                <div class="time sold">
                                    <i class="bi bi-check-circle"></i>
                                    Sold
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    </div>

    <?php include __DIR__ . "/partials/_navbar.php"; ?>
    <?php include __DIR__ . "/partials/_timebar.php"; ?>

</body>

</html>