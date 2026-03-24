<?php ob_start(); ?>

<div class="content-wrapper">
    <main class="main-content">

         <?php require "partials/open_item/_main_image.php"; ?>

        <?php require "partials/open_item/_item_details.php"; ?>

        <?php require "partials/open_item/_thumbnails.php"; ?>

        <?php if (!empty($show_bid_history)): ?>
            <?php require "partials/open_item/_bid_history.php"; ?>
        <?php endif; ?>



    </main>

    <aside class="sidebar-content">
         <section class="section pricing-section">
            <h3 class="section-title-small pricing-title">Pricing</h3>
            <?php require "partials/open_item/pricing/_pricing_display.php"; ?>
            <hr class="divider-line">
            <?php require "partials/open_item/pricing/_pricing_buttons.php"; ?>
            <?php require "partials/open_item/pricing/_pricing_message.php"; ?>
        </section>

        <?php require "partials/open_item/_seller_info.php"; ?>

        <?php require "partials/open_item/manage/_manage_item.php"; ?>
    </aside>
</div>

<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>