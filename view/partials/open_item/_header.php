<header>
    <div class="header-content">
        <a href="<?= $back_url ?? 'browse_items' ?>" class="header-back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-title">
            <i class="bi bi-cart"></i>
            <?= $header_title ?? 'Item' ?>
        </h1>
        <div class="header-spacer"></div>
    </div>
</header>