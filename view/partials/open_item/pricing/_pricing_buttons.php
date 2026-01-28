<?php
if (!$showButtons) return;
?>


<?php if ($item->get_Is_Auction()): ?>
<form class="bid-form" method="post" action="bid/create">
    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
    <div class="bid-input-group">
        <span>€</span>
        <input type="number" name="amount" step="0.01" 
               min="<?= $minBidAmount ?>" value="<?= $minBidAmount ?>" 
               required <?= $buttonsDisabled ? 'disabled' : '' ?>>
    </div>
    <button type="submit" class="btn-place-bid" <?= $buttonsDisabled ? 'disabled' : '' ?>>
        Place Bid
    </button>
</form>
<?php endif; ?>


<?php if ($item->get_Has_buy_now_price()): ?>
<form method="post" action="bid/create">
    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
    <input type="hidden" name="amount" value="<?= $item->get_Buy_Now_Price() ?>">
    
    <?php 
    $isDirectSaleOnly = $item->get_Is_Direct_Sale() && !$item->get_Is_Auction();
    $btnClass = $isDirectSaleOnly ? 'btn-place-bid' : 'btn-buy-now';
    $btnText = $isDirectSaleOnly ? 'BUY NOW' : 'Buy Now at € ' . number_format($item->get_Buy_Now_Price(), 2, ',', '.');
    ?>
    
    <button type="submit" class="<?= $btnClass ?>" <?= $buttonsDisabled ? 'disabled' : '' ?>>
        <?= $btnText ?>
    </button>
</form>
<?php endif; ?>