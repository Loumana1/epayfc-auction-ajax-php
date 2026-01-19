<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Browser</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/browser.css">
</head>
<body>

   <header class = "header" >
    <h1>Browse</h2>
        <span class="cart">🛒</span>
   </header> 
    
<main>
    <div class="participating">
        <h2>Items I'm Participating in</h2>
        <div class="item-list"> 
            <?php if (!empty($participating_items)): ?>
                <?php foreach ($participating_items as $item): ?>
                    <div class="item-card"> 
                        <a href="open_item/index/<?= $item['id'] ?>">
                            <?php if ($item['pic_path']): ?>
                                <img src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg', $item['pic_path']) ?>" class="item-image">
                            <?php else: ?>
                                <div class="no-pic">No Pic</div>
                            <?php endif; ?>
                            <div class="item-info">
                                <h3 class="item-title"><?= htmlspecialchars($item['title']) ?></h3>
                                
                                <div class="item-labels">
                                    <?php if ($item['is_highest_bidder']): ?>
                                        <span class="label label-highest-bidder">Highest Bidder</span>
                                    <?php elseif ($item['has_bid']): ?>
                                        <span class="label label-bidder">Bidder</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['is_auction']): ?>
                                        <span class="label label-auction">Auction</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['has_buy_now']): ?>
                                        <span class="label label-buy-now">Buy Now</span>
                                    <?php endif; ?>
                                </div>
                                <div class="item-details">
                                    <?php if ($item['picture_count'] > 0): ?>
                                        <span class="picture-count"><?= $item['picture_count'] ?> image<?= $item['picture_count'] > 1 ? 's' : '' ?></span>
                                    <?php endif; ?>
                                    
                                    <span class="seller">Listed by <?= htmlspecialchars($item['seller_pseudo']) ?></span>
                                </div>
                                
                                <div class="item-pricing">
                                    <?php if ($item['has_buy_now'] && $item['buy_now_price']): ?>
                                        <span class="price">€<?= number_format($item['buy_now_price'], 2, '.', '') ?></span>
                                    <?php elseif ($item['starting_bid']): ?>
                                        <span class="price">€<?= number_format($item['starting_bid'], 2, '.', '') ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['max_bid']): ?>
                                        <span class="max-bid">Highest: €<?= number_format($item['max_bid'], 2, '.', '') ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="item-time">
                                    <span class="time-remaining"><?= htmlspecialchars($item['time_remaining']) ?> left</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No active bids yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="available">
        <h2>Other Available Items</h2> 
        <div class="item-list">
            <?php if (!empty($available_items)): ?>
                <?php foreach ($available_items as $item): ?>
                    <div class="item-card">
                        <a href="item/open/<?= $item['id'] ?>">
                            <?php if ($item['pic_path']): ?>
                                <img src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg', $item['pic_path']) ?>" class="item-image">
                            <?php else: ?>
                                <div class="no-pic">No Pic</div>
                            <?php endif; ?>
                            <div class="item-info">
                                <h3 class="item-title"><?= htmlspecialchars($item['title']) ?></h3>
                                
                                <div class="item-labels">
                                    <?php if ($item['is_auction']): ?>
                                        <span class="label label-auction">Auction</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['is_direct_sale']): ?>
                                        <span class="label label-direct-sale">Direct Sale</span>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['has_buy_now']): ?>
                                        <span class="label label-buy-now">Buy Now</span>
                                    <?php endif; ?>
                                </div>
                                <div class="item-details">
                                    <?php if ($item['picture_count'] > 0): ?>
                                        <span class="picture-count"><?= $item['picture_count'] ?> image<?= $item['picture_count'] > 1 ? 's' : '' ?></span>
                                    <?php endif; ?>
                                    
                                    <span class="seller">Listed by <?= htmlspecialchars($item['seller_pseudo']) ?></span>
                                </div>
                                
                                <div class="item-pricing">
                                    <?php if ($item['has_buy_now'] && $item['buy_now_price']): ?>
                                        <span class="price">€<?= number_format($item['buy_now_price'], 2, '.', '') ?></span>
                                    <?php elseif ($item['starting_bid']): ?>
                                        <span class="price">€<?= number_format($item['starting_bid'], 2, '.', '') ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['max_bid']): ?>
                                        <span class="max-bid">Highest: €<?= number_format($item['max_bid'], 2, '.', '') ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="item-time">
                                    <span class="time-remaining"><?= htmlspecialchars($item['time_remaining']) ?> left</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No other items available.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

    <footer> <div class ="footer">
    <nav class="footer-nav">

        <a href="#" class="nav-item active">
            <span class="icon">🔎</span>
            <span class="label">Browse</span>
        </a>

        <a href="#" class="nav-item">
            <span class="icon">🏠</span>
            <span class="label">My Items</span>
        </a>

        <a href="#" class="nav-item">
            <span class="icon">➕</span>
            <span class="label">Add Offer</span>
        </a>

        <a href="#" class="nav-item">
            <span class="icon">⚙️</span>
            <span class="label">Profile</span>
        </a>

    </nav>
    </div>
</footer>

</body>
</html>
