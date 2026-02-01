<?php
require_once "utils/AppTime.php";
require_once "framework/Configuration.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $item->get_Title() ?></title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/open_item.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <!--------HEADER-------->
        <?php include __DIR__ . "/partials/_header.php"; ?>


         <div class="content-wrapper">
                <?php
                $info_message = $bid_success_message ?? $delete_item_blocked_message ?? $manage_images_blocked_message ?? null;
                if (!empty($info_message)):
                ?>
                    <div class="bid-success-overlay">
                        <div class="bid-success-box">
                            <p class="bid-success-message"><?= $info_message?></p>
                            <a href="bid/ack/<?= (int)$item->get_Id() ?>" class="bid-success-ok">OK</a>
                        </div>
                    </div>
                <?php endif; ?>

                    <main class="main-content">

                            <?php include __DIR__ . "/partials/open_item/_main_image.php"; ?>
                            <?php include __DIR__ . "/partials/open_item/_item_details.php"; ?>
                            <?php include __DIR__ . "/partials/open_item/_thumbnails.php"; ?>

                            <?php if (!empty($showBidHistory)): ?>
                                <?php include __DIR__ . "/partials/open_item/_bid_history.php"; ?>
                            <?php endif; ?>

                    </main>


                    <aside class="sidebar-content">
                        <section class="section pricing-section">
                            <h3 class="section-title-small pricing-title">Pricing</h3>
                            <?php include __DIR__ . "/partials/open_item/pricing/_pricing_display.php"; ?>
                            <hr class="divider-line">
                            <?php include __DIR__ . "/partials/open_item/pricing/_pricing_buttons.php"; ?>
                            <?php include __DIR__ . "/partials/open_item/pricing/_pricing_message.php"; ?>
                        </section>
                        
                        <?php include __DIR__ . "/partials/open_item/_seller_info.php"; ?>
                        <?php include __DIR__ . "/partials/open_item/manage/_manage_item.php"; ?>
                    </aside>

                        
                
                
                        
        </div>

                     
        <?php include __DIR__ . "/partials/_navbar.php"; ?>
         <?php include __DIR__ . "/partials/_timebar.php"; ?>
            




</body>
</html>