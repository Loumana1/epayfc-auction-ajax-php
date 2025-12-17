<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($item->get_Title()) ?> - Item Details</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <header>
        <h1>Item open</h1>
    </header>
    <!--------------------------------------->
<!-- A faire
   -Creer les class et id pour chaque div ou presque
 - Bien delimiter et colorer  (css) toutes les section avant php
      
--> 
<!----------------------------------------> 

    <div class="content-wrapper">
    
<!--------------------------------------->
<!-- Main container--> 
<!----------------------------------------> 
        <main class="main-content">
            
            <!--Bloc  grande image -->
            <section>

                        <?php if (!empty($pictures)): ?>
                    <img   src="<?= $web_root . $pictures[0]['picture_path'] ?>" 
                            alt="<?=  htmlspecialchars($item->get_Title()) ?>"

                            class="main-item-image">
                        <?php else: ?>
                        <div >Image paas là! </div>
                        <?php endif; ?>
            </section>

                    <!-- Bloc item description section -->
            <section class="item-details-section" >
                        <h2><?= htmlspecialchars($item->get_Title()) ?></h2>

                <p class="item-description"><?= htmlspecialchars($item->get_Description() ?? 'No description') ?></p>
                        



                        <!--button type de vents section -->
                    <div class="button-type-of-sale">

                        <?php if ($item->get_Is_Auction()): ?>
                                <span class="tag auction-sale-tag">Auction</span>
                        <?php endif; ?>

                        <?php if ($item->get_Is_Direct_Sale()): ?>

                            <span class="tag direct-sale-tag">Direct Sale</span>
                        <?php endif; ?>

                    </div>

            
                        <div class="item-dates">

                            <p><strong>Start:</strong> <?= date('d/m/Y H:i:s', 
                            strtotime($item->get_Created_At())) ?></p>

                            <p><strong>Ends:</strong> <?= $item->get_End_At() ? date('d/m/Y H:i:s',
                             strtotime($item->get_End_At())) : 'N/A' ?></p>

                        </div>


            </section>

               <!-- bloc images additionnel -->
            <?php if (count($pictures) > 1): ?>
            <section class="additional-images-section">
                <h3 class="additional-images-tittle">Additional Images</h3>

                <div class="thumbnail-gallery">

                    <?php foreach ($pictures as $index => $picture): ?>

        

                        <img src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg',
                                    $picture['picture_path']) ?>" 
                                    alt="Thumbnail <?= $index + 1 ?>"
                       
                         >
             <?php endforeach; ?>    
                </div>
            </section>
            <?php endif; ?>
        </main>




<!--------------------------------------->
<!-- Sidebar -->
 <!---------------------------------------->
        <aside class="sidebar-content">


<!----- Box Pricing section ------------->

 <section class="pricing-section">
        <h3 class="pricing-title">Pricing</h3>

                <!--- Prix ----->
                <?php if ($item->has_bids ): ?>

                    <div class="price-row">
                        <label class="price-label" >Current Bid</label>
                    <p class="price-value-current-bid">€ <?= number_format($item->get_Max_Bid(), 2, ',', '.') ?></p>
                    </div>
                <?php elseif($item->get_Is_Auction()): ?>


                    <div  class="price-row">
                        <label class="price-label">Starting Bid</label>
                        <p class="price-value-current-bid">€ <?= number_format($item->get_Starting_Bid(), 2, ',', '.') ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($item->get_Buy_Now_Price()): ?>
                    <div class="price-row">
                        <label class="price-label">Buy Now</label>
                        <p class="price-value">€ <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?></p>
                    </div>
                <?php endif; ?>


                <!-- Formulmaire  BId-->
                 <!-- if ($isOpen && !$isOwner && $currentUser)-->
                <?php if ($isOpen && $item->get_Is_Auction()): ?>
                
                        <form class="bid-form" >

                            <div class="bid-input-group">
                                <span >€</span>
                                <input type="number" required>

                            </div>
                            <button type="submit" class="btn-place-bid">Place Bid</button>
                        </form>
          <?php endif; ?>
                              <?php if ($item->get_Buy_Now_Price()): ?>
                    
                            <button type="submit" class="btn-buy-now">
                                Buy Now at € <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?>
                            </button>
                       
                    <?php endif; ?>
</section>


         
                
                <!-- Box info Seller -->

                
                     <!--------------------------------------->
                    <!-- A faire
                    

                    --> 
                    <!----------------------------------------> 


            <?php if ($seller): ?>
            <section class="seller-section">
                <h3>Seller Information</h3>
                <div class="seller-info" >
                    <?php if ($seller->picture_path): ?>
                        <img src="<?= $web_root . str_replace('.jpg',
                         '_thumbnail.jpg', $seller->picture_path) ?>" 
                             class="seller-pic">
                    <?php else: ?>
                        <div  class="seller-pic placeholder"> </div>
                    <?php endif; ?>
                    <div  class="seller-details">
                        <h2 class="seller-name"><?= htmlspecialchars($seller->pseudo) ?></h2>
                        <p class="seller-status">Member</p>
                        
                    </div>
                </div>

            </section>
            <?php endif; ?>

        </aside>
    </div>

    <nav class="navBar">


<a href="browse_items" >
    <span >🔍</span>
    <span>Browse</span>
</a>

    <a>
        <span >🏠</span>
        <span>My Items</span>
    </a>
<a>
    <span >➕</span>
    <span>Add Offer</span>
</a>
    <a>
        <span>👤</span>
        <span>Profile</span>
    </a>

</nav>




</body>
</html>