<section class="section item-details-section">
    <h2><?= $item->get_Title() ?></h2>
    <p class="item-description"><?= $item->get_Description() ?? 'No description' ?></p>
    
    <div class="button-type-of-sale">
        <?php if ($item->get_Is_Auction()): ?>
            <span class="tag auction-sale-tag">Auction</span>
        <?php endif; ?>
        <?php if ($item->get_Is_Direct_Sale()): ?>
            <span class="tag direct-sale-tag">Direct Sale</span>
        <?php endif; ?>
    </div>
    
    <div class="item-dates">
        <p><strong>Start:</strong> <?= date('d/m/Y H:i:s', strtotime($item->get_Created_At())) ?></p>
        <p><strong>Ends:</strong> <?= $item->get_End_At() ? date('d/m/Y H:i:s', strtotime($item->get_End_At())) : 'N/A' ?></p>
    </div>
</section>