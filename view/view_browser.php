<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Browser</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
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
                        <a href="item/open/<?= $item['id'] ?>">
                            <?php if ($item['pic_path']): ?>
                                <img src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg', $item['pic_path']) ?>" class="item-image">
                            <?php else: ?>
                                <div class="no-pic">No Pic</div>
                            <?php endif; ?>
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
