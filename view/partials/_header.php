<?php
// Variables attendues
$header_title = $header_title ?? 'Page';
$header_icon = $header_icon ?? 'bi-house';
$back_url = $back_url ?? null ;
$header_right_icon = $header_right_icon ?? null;
$header_right_url = $header_right_url ?? null;
$header_right_text = $header_right_text ?? null;
$header_right_form_id = $header_right_form_id ?? null;
?>


<header>
    <div class="header-content">
            <?php if (!empty($back_url)): ?>
            <a href="<?= $back_url ?>" class="header-back-btn">
                <i class="bi bi-arrow-left"></i>
            </a>
            <?php else: ?>
                <div class="header-back-spacer"></div>
            <?php endif; ?>



        <h1 class="header-title">
        <i class="bi <?= $header_icon ?>"></i>
            <?= $header_title ?>
        </h1>
        <?php if ($header_right_icon && $header_right_form_id): ?>
            <button type="submit" form="<?= $header_right_form_id ?>" class="header-right-btn">
                <i class="bi <?= $header_right_icon ?>"></i>
                <?php if ($header_right_text): ?>
                    <span><?= $header_right_text ?></span>
                <?php endif; ?>
            </button>
        <?php elseif ($header_right_icon && $header_right_url): ?>
            <a href="<?= $header_right_url ?>" class="header-right-btn">
                <i class="bi <?= $header_right_icon ?>"></i>
                <?php if ($header_right_text): ?>
                    <span><?= $header_right_text ?></span>
                <?php endif; ?>
            </a>
        <?php else: ?>
            <div class="header-spacer"></div>
        <?php endif; ?>
    </div>
</header>