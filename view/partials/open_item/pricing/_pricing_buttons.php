<?php
if (!$show_buttons) return;
?>

<?php if ($item->get_Is_Auction()): ?>
<form class="bid-form" method="post" action="bid/create">
    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
    <div class="bid-input-group">
        <span>€</span>
        <input type="number" name="amount" step="0.01" 
               min="<?= $min_bid_amount ?>" value="<?= $min_bid_amount ?>" 

               required 
               <?= $buttons_disabled ? 'disabled' : '' ?>>
    </div>
    <button type="submit" class="btn-place-bid" <?= $buttons_disabled ? 'disabled' : '' ?>>
        Place Bid
    </button>
</form>
<?php endif; ?>


<?php if ($item->get_Has_buy_now_price()): ?>
<form method="post" action="bid/create">
    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
    <input type="hidden" name="amount" value="<?= $item->get_Buy_Now_Price() ?>">
    
    <?php 
    $is_direct_sale_only = $item->get_Is_Direct_Sale() && !$item->get_Is_Auction();
    $btn_class = $is_direct_sale_only ? 'btn-place-bid' : 'btn-buy-now';
    $btn_text = $is_direct_sale_only ? 'BUY NOW' : 'Buy Now at € ' . number_format($item->get_Buy_Now_Price(), 2, ',', '.');
    ?>
    
    <button type="submit" class="<?= $btn_class ?>" <?= $buttons_disabled ? 'disabled' : '' ?>>
        <?= $btn_text ?>
    </button>
</form>
<?php endif; ?>