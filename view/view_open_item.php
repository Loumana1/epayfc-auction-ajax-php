<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $item->get_Title() ?> - Item Details</title>
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
                    <img   src="<?= $pictures[0]['picture_path'] ?>" 
                            alt="<?=  $item->get_Title() ?>"

                            class="main-item-image">
                        <?php else: ?>
                        <div >Image paas là! </div>
                        <?php endif; ?>
            </section>

                    <!-- Bloc item description section -->
            <section class="section item-details-section">
                        <h2><?= $item->get_Title() ?></h2>

                <p class="item-description"><?= $item->get_Description() ?? 'No description' ?></p>
                        



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
                <h3 class="section-title">Additional Images</h3>

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
  






        <!-------Box  Bid History  --------->
<section class="section bid-history-section">
    <div class="bid-section-header">
        <h3 class="section-title">Bid History</h3>
        <?php if (!empty($bids)): ?>
            <span class="bid-count-tag"><?= count($bids) ?> entries</span>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($bids)): ?>
        <div class="bid-list">

            <?php foreach ($bids as $bid): ?>
                <div class="bid-row">

                    <div class="bid-info">
                    <span class="bidder-name"><?=$bid['pseudo'] ?></span>

                    <span class="bid-date"><?= date('d/m/Y H:i:s', strtotime($bid['created_at'])) ?></span>
                    </div>

                    <span class="bid-amount">€ <?= number_format($bid['amount'], 2, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p>No bids </p>
    <?php endif; ?>
</section>




        </main>




<!--------------------------------------->
<!-- Sidebar -->
 <!---------------------------------------->
        <aside class="sidebar-content">


<!----- Box Pricing section ------------->

 <section class="section pricing-section">
        <h3 class="section-title pricing-title">Pricing</h3>

                <!--- Prix ----->
                <?php if ($item->get_Is_Auction()):?>

                        <?php if ($item->has_bids  ): ?>

                            <div class="price-row">
                                <label class="price-label" >Current Bid</label>
                            <p class="price-value-current-bid">€ <?= number_format($item->get_Max_Bid(), 2, ',', '.') ?></p>
                            </div>
                        <?php else: ?>


                            <div  class="price-row">
                                <label class="price-label">Starting Bid</label>
                                <p class="price-value-current-bid">€ <?= number_format($item->get_Starting_Bid(), 2, ',', '.') ?></p>
                            </div>
                        <?php endif; ?>

                <?php endif; ?>
            

                <?php if ($item->get_Buy_Now_Price() ): ?>
                    <div class="price-row">
                        <label class="price-label">Buy Now</label>
                        <p class="price-value">€ <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?></p>
                    </div>
                <?php endif; ?>


                <!-- Button place bid , Formulmaire -->

                <?php if ($isOpen && $item->get_Is_Auction() && $currentUser && !$isOwner): ?>
                
                        <form class="bid-form" >

                            <div class="bid-input-group">
                                <span >€</span>
                                <input type="number"
                                min="<?= $minBidAmount ?>" 
                                 required>

                            </div>
                            <button type="submit" class="btn-place-bid">Place Bid</button>
                        </form>

                        <?php elseif($isOpen) : ?>

                             <!-- Si l'utilisateur n'est pas connecté et vente dispo-->
                        <button type="button" class="btn-place-bid">log in to place a bid or buy now.</button>

                        <?php else: ?>

                            <span class="tag auction-sale-tag">Sale finished. item not available</span>

          <?php endif; ?>


                <!---Button Buy now---->
                              <?php if ($item->get_Buy_Now_Price()&& $isOpen && !$item->buy_now_reached && $currentUser && !$isOwner): ?>
                    
                            <button type="submit" class="btn-buy-now">
                                Buy Now at € <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?>
                            </button>
                       
                    <?php endif; ?>


                    
                        <!-- si l'utilisateur est le proprietaire -->
    <?php if ($isOwner && $isOpen): ?>

        <p class="owner-message">You cannot bid on your own item.</p>
    <?php endif; ?>


</section>


         
                
                <!-- Box info Seller -->


            <?php if ($seller): ?>
            <section class="section seller-section">
                <h3 class="section-title">Seller Information</h3>
                <div class="seller-info" >
                    <?php if ($seller->has_Picture()): ?>
                        <img src="<?= $seller->get_Thumbnail_Path() ?>" 
                             class="seller-pic">
                    <?php else: ?>
                        <div  class="seller-pic placeholder"> </div>
                    <?php endif; ?>

                    <div  class="seller-details">
                        <h2 class="seller-name"><?= $seller->pseudo ?></h2>
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