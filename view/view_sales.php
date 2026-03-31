
<?php ob_start(); ?>


<div class="content-wrapper">
    <main class="main-content">

        <section class="page-header">
            <div class="page-header-text">
                <h2 class="page-title">Completed Sales</h2>
                <p class="page-subtitle">A snapshot of the deals you've wrapped up.</p>
            </div>
            <span class="badge badge-green">
                <!--constante linguistique, ok pou nb dur--> 
                <?= $sales_count ?> sale<?= $sales_count !== 1 ? 's' : '' ?>
            </span>
        </section>

        <?php require "partials/sales/_sales_stats.php"; ?>

        <?php if (!empty($sale_cards)): ?>
            <section class="sales-grid">
                <?php foreach ($sale_cards as $card): ?>
                        <?php require "partials/sales/_sale_card.php"; ?>
               
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <?php require "partials/sales/_no_sales.php"; ?>
        <?php endif; ?>


    </main>
</div>
<?php $content = ob_get_clean(); ?>
<?php require "view_layout.php"; ?>
