<?php
require_once "utils/AppTime.php";
require_once "framework/Configuration.php";
?>
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
        <h3 class="section-title-small">Bid History</h3>
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
        <p>No bids yet. Be the first to participate!</p>
    <?php endif; ?>
</section>




        </main>




<!--------------------------------------->
            <!-- Sidebar -->
 <!---------------------------------------->
        <aside class="sidebar-content">


<!----- Box Pricing section ------------->

 <section class="section pricing-section">
        <h3 class="section-title-small pricing-title">Pricing</h3>

                <!--- Bloc dynamic Prix ----->
            
                <!----- cas possibble:
                - Annonce ouverte, utilisateur non créateur, pas de prix d'achat immédiat et pas encore d'enchère 
                   -
                   -

                --->
                <?php if ($item->get_Is_Auction() ):?>

                

                            <div class="price-row">
                                <label class="price-label" >Current Bid €</label>
                                <p class="price-value-current-bid"> € <?= number_format($item->get_Max_Bid() ?? $minBidAmount, 2, ',', '.') ?> </p>
                            </div>
                            
                            <?php if ($item->get_Buy_Now_Price() ): ?>
                                    <div class="price-row">
                                        <label class="price-label">Buy Now</label>
                                        <p class="price-value">€ <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?></p>
                                    </div>
                                    <?php else: ?>


                                            <div  class="price-row">
                                                <label class="price-texte-small-grey" >Starting Bid € <?= number_format($item->get_Starting_Bid(), 2, ',', '.') ?> </label>
                                            </div>

                                <?php endif; ?>
                <?php else: ?> 
              
                    <!---------------- Buy now seulement --------------->

                                 <div class="price-row">
                                        <label class="price-label">Price</label>
                                        <p class="price-value-current-bid">€ <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?></p>
                                    </div> 
     


                <?php endif; ?>
            

   


                <!-- Button place bid , Formulmaire -->
                <hr class="divider-line">
                <?php if ($isOpen && $item->get_Is_Auction() ): ?>
            
                         <form class="bid-form <?= $isOwner ? 'disabled-form' : '' ?>" 
          method="post" 
          action="<?= $web_root ?>bid/create"
          <?= $isOwner ? 'onsubmit="return false;"' : '' ?>>
                            <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">

                            <div class="bid-input-group <?= $isOwner ? 'disabled' : '' ?>">
                                <span >€</span>
                                <input type="number"
                               name="amount" 
                                step="1.0" 
                                min="<?= number_format($minBidAmount, 2, '.', '') ?>" 
                                value="<?= number_format($minBidAmount, 2, '.', '') ?>" 
                                required>

                            </div>
                            <button type="submit" class="btn-place-bid"<?= $isOwner ? 'disabled' : '' ?>"
                            <?= $isOwner ? 'disabled' : '' ?>>Place Bid
                        </button>
                <?php endif ?>
                <!---Button Buy now---->
                        <?php if ($item->get_Has_buy_now_price() && $isOpen && $item->get_Is_Direct_Sale() ): ?>

                                    <form method="post"
                                     action="bid/create_bid"

                                                class="<?= ($isOwner || !$currentUser) ? 'disabled-form' : '' ?>"
                                                <?= ($isOwner || !$currentUser) ? 'onsubmit="return false;"' : '' ?>>

                                        <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
                                        <input type="hidden" name="amount" value="<?= $item->get_Buy_Now_Price() ?>">

                                    <button type="submit"

                                               class="btn-place-bid <?= ($isOwner || !$currentUser) ? 'disabled' : '' ?>"
                                              <?= ($isOwner || !$currentUser) ? 'disabled' : '' ?>>
                                              BUY NOW</button>
                                    </form>

                            <?php elseif($item->get_Has_buy_now_price() && $item->get_Is_Auction() && $isOpen  ): ?>
                                        <form method="post"
                                         action="bid/create_bid"

                                                 class="<?= ($isOwner || !$currentUser) ? 'disabled-form' : '' ?>"
                                             <?= ($isOwner || !$currentUser) ? 'onsubmit="return false;"' : '' ?>>

                                            <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
                                            <input type="hidden" name="amount" value="<?= $item->get_Buy_Now_Price() ?>">

                                                <button type="submit"
                                                 class="btn-buy-now" <?= ($isOwner || !$currentUser) ? 'disabled' : '' ?>"
                                                     <?= ($isOwner || !$currentUser) ? 'disabled' : '' ?>>
                                                Buy Now at € <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?>
                                            </button> </form>

                                       
                                            
                             <?php endif; ?>

                            <?php if ($isOwner && $isOpen): ?>
                                <p class="price-texte-small-grey">You cannot bid on your own listing.</p>
                                 <?php elseif (!$currentUser): ?>
                                     <p class="price-texte-small-grey">Please log in to place a bid</p>
                            <?php endif; ?>
                 





            <?php if(!$isOpen): ?>

                

                            <?php if ($isHighestBidder && $currentUser): ?>
                                    <p class="price-texte-small-grey">Congratulation! You purchased this item for € <?= number_format($item->get_Max_Bid(), 2, ',', '.') ?></p>

                                <?php elseif ($isOwner && $item->has_bids): ?>
                                    <p class="price-texte-small-grey"><?= ModelItems::get_Highest_Bidder_Pseudo($itemId)  ?>

                                        won this item for €<?= number_format($item->get_Max_Bid(), 2, ',', '.') ?></p>

                                <?php elseif ($isOwner && !$item->has_bids): ?>
                                    <p class="price-texte-small-grey">This listing ended without a buyer.</p>

                                <?php elseif ($item->has_bids): ?>
                                    <p class="price-texte-small-grey">Final price: €<?= number_format($item->get_Max_Bid(), 2, ',', '.') ?></p>

                                    <?php else: ?>
                                        <p class="price-texte-small-grey">  item not available for sale.</p>
                                <?php endif; ?>

            <?php endif; ?>





                    


</section>


         
                
                <!-- Box info Seller -->


            <?php if ($seller): ?>
            <section class="section seller-section">
                <h3 class="section-title-small">Seller Information</h3>
                <div class="seller-info" >
                    <?php if ($seller->has_Picture()): ?>
                        <img src="<?= $seller->get_Thumbnail_Path() ?>" 
                             class="seller-pic">
                    <?php else: ?>
                        <div  class="seller-pic placeholder"> </div>
                    <?php endif; ?>

                    <div  class="seller-details">
                        <h2 class="seller-name"><?= $seller->pseudo ?></h2>
                        <p class="price-texte-small-grey">Member</p>
                        
                    </div>
                </div>

            </section> 
            <?php endif; ?>

            <!-- Section Manage item pour le owner -->
<?php if ($isOwner): ?>
    <div class="owner-section">
        <h4 class="owner-section-title">Manage Your Item</h4>
        
      
            <!-- Item peut être modif car pas d'enchère 
              Afaire : 
               -rajoutr des cnditions 
            -->


            <div class="owner-actions">
                <a href="<?= $web_root ?>add_edit_item?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
                    Edit Item Details
                </a>
                <a href="<?= $web_root ?>manage_images?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
                    Manage Images
                </a>
                <a href="<?= $web_root ?>delete_confirm?param1=<?= $item->get_Id() ?>" class="btn-delete-item">
                    Delete Item
                </a>
            </div>



    </div>
<?php endif; ?>

        </aside>
    </div>

    <nav class="navBar navBar-principal">


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

    <nav class="navBar navBar-time">
        <div class="time-display">
            <span class="time-text"><?= date('d/m/y H:i', strtotime(AppTime::get_current_datetime())) ?></span>
        </div>
        <div class="time-controls">
            <form method="post" action="time/advance" style="display: inline;">
                <input type="hidden" name="amount" value="1">
                <input type="hidden" name="unit" value="hour">
                <button type="submit" class="time-btn">+1h</button>
            </form>
            <form method="post" action="time/advance" style="display: inline;">
                <input type="hidden" name="amount" value="1">
                <input type="hidden" name="unit" value="day">
                <button type="submit" class="time-btn">+1day</button>
            </form>
            <form method="post" action="time/advance" style="display: inline;">
                <input type="hidden" name="amount" value="1">
                <input type="hidden" name="unit" value="week">
                <button type="submit" class="time-btn">+1week</button>
            </form>
            <form method="post" action="time/advance" style="display: inline;">
                <input type="hidden" name="amount" value="1">
                <input type="hidden" name="unit" value="month">
                <button type="submit" class="time-btn">+1month</button>
            </form>
            <form method="post" action="time/advance" style="display: inline;">
                <input type="hidden" name="amount" value="-1">
                <input type="hidden" name="unit" value="month">
                <button type="submit" class="time-btn">-1month</button>
            </form>
            <form method="post" action="time/reset" style="display: inline;">
                <button type="submit" class="time-btn time-btn-reset">Reset</button>
            </form>
        </div>
    </nav>


    




</body>
</html>