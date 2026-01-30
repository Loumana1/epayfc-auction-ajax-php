<?php
// Variables attendues (avec valeurs par défaut)
$header_title = $header_title ?? 'Page';
$header_icon = $header_icon ?? 'bi-house';
$back_url = $back_url ?? 'browser';
?>


<header>
    <div class="header-content">
        <a href="<?= $back_url ?>" class="header-back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-title">
        <i class="bi <?= $header_icon ?>"></i>
            <?= $header_title ?>
        </h1>
        <div class="header-spacer"></div>
    </div>
</header>