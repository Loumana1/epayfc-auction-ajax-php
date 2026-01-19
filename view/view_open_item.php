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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

    <header>
        <div class="header-content">
            <a href="browser" class="header-back-btn">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="header-title">
                <i class="bi bi-cart"></i>
                Item open
            </h1>
            <div class="header-spacer"></div>
        </div>
    </header>


         <div class="content-wrapper">

        <!--------------------------------------->
        <!-- Main container--> 
        <!----------------------------------------> 
            <main class="main-content">

                
                <!--Bloc  grande image -->
                <section>




                    <?php 
                    
                        if (empty($pictures)) {
                            $mainPicturePath = null;
                        } else {
                 
                            
                            $mainPicturePath = $pictures[$selectedImg]['picture_path'] ?? $pictures[0]['picture_path'];
                        }


                    ?>
                    <?php if ($mainPicturePath): ?>
                        <img src="<?= $mainPicturePath ?>" 
                            alt="<?= htmlspecialchars($item->get_Title()) ?>"
                            class="main-item-image">
                    <?php else: ?>
                        <div class="image-placeholder">
                        <span class="placeholder-text">No image available for this item </span>
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
                                    <a href="open_item/index/<?= $item->get_Id() ?>/<?= $index ?>">
                                        <img class="thumbnail <?= $selectedImg === $index ? 'active' : '' ?>" 
                                            src="<?= $web_root . str_replace('.jpg', '_thumbnail.jpg', $picture['picture_path']) ?>" 
                                            alt="Thumbnail <?= $index + 1 ?>">
                                    </a>
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
                    
                        <?php if ($item->get_Is_Auction() ):?>

                        

                                <div class="price-row">
                                    <label class="price-label" >Current Bid €</label>
                                    <p class="price-value-current-bid"> € <?= number_format($maxBidTime ?? $minBidAmount, 2, ',', '.') ?> </p>
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
                

                                <div class="price-row">
                                        <label class="price-label">Price</label>
                                        <p class="price-value-current-bid">€ <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?></p>
                                    </div> 
            


                        <?php endif; ?>
                    

                        <hr class="divider-line">


                        <!---------------Buttons----------------->
                        <?php if ($showButtons): ?>

                            <!--place bid  -->
                            <?php if ($item->get_Is_Auction()): ?>
                                <form class="bid-form" method="post" action="bid/create">
                                    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
                                    <div class="bid-input-group">
                                        <span>€</span>
                                        <input type="number"
                                            name="amount" 
                                            step="0.01" 
                                            min="<?= $minBidAmount ?>" 
                                            value="<?= $minBidAmount ?>" 
                                            required
                                            <?= $buttonsDisabled ? 'disabled' : '' ?>>
                                    </div>
                                    <button type="submit" class="btn-place-bid" <?= $buttonsDisabled ? 'disabled' : '' ?>>
                                        Place Bid
                                    </button>
                                </form>
                            <?php endif; ?>

                            <!-- BUY NOW -->
                            <?php if ($item->get_Has_buy_now_price()): ?>
                                <form method="post" action="bid/create">
                                    <input type="hidden" name="item_id" value="<?= $item->get_Id() ?>">
                                    <input type="hidden" name="amount" value="<?= $item->get_Buy_Now_Price() ?>">
                                    
                                    <?php if ($item->get_Is_Direct_Sale() && !$item->get_Is_Auction()): ?>
                                        <button type="submit" class="btn-place-bid" <?= $buttonsDisabled ? 'disabled' : '' ?>>
                                            BUY NOW
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn-buy-now" <?= $buttonsDisabled ? 'disabled' : '' ?>>
                                            Buy Now at € <?= number_format($item->get_Buy_Now_Price(), 2, ',', '.') ?>
                                        </button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>

                            <?php endif; ?>

            

                            <?php if ($statusMessage): ?>
                            <p class="price-texte-small-grey">
                                <?= $statusMessage ?>
                            </p>
                        <?php endif; ?>
                                                
                      


                            


            </section>


         
                
    <!------------------- Bloc info Seller ------------------------>


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
                            <h2 class="seller-name"><?= $seller->get_Pseudo() ?></h2>
                            <p class="price-texte-small-grey">Member</p>
                            
                        </div>
                    </div>

                </section> 
            <?php endif; ?>

            <!-- Section Manage item pour le owner -->
            <?php if ($isOwner): ?>
                <div class="owner-section">
                    <h4 class="owner-section-title">Manage Your Item</h4>

                        <div class="owner-actions">

                        <?php if ($hasActiveBids || $itemPurchased): ?>
                            <span class="btn-manage-item btn-disabled">Edit Item Details</span>
                        <?php else: ?>
                            <a href="add_edit_item?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
                                Edit Item Details
                            </a>
                        <?php endif; ?>



                            <?php if ($itemPurchased): ?>
                                <span class="btn-manage-item btn-disabled">Manage Images</span>
                            <?php else: ?>
                            <a href="manage_images?param1=<?= $item->get_Id() ?>" class="btn-manage-item">
                                Manage Images
                            </a>
                             <?php endif; ?>


                            <?php if ($itemPurchased): ?>
                                <span class="btn-delete-item btn-disabled ">Delete Item</span>
                            <?php else: ?>
                            <a href="delete_confirm/index/<?= $item->get_Id() ?>" class="btn-delete-item">
                                Delete Item
                            </a>
                            <?php endif; ?>
                        </div>



                </div>
            <?php endif; ?>

        </aside>
    </div>

    <nav class="navBar navBar-principal">


            <a href="browser" >
            <i class="bi bi-search"></i>
                <span>Browse</span>
            </a>
    <?php if ($currentUser): ?>
                <a>
           
                <i class="bi bi-house"></i>
                    <span>My Items</span>
                </a>
            <a>
            <i class="bi bi-plus-circle"></i>
                <span>Add Offer</span>
            </a>
                <a>
                <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
    <?php else: ?>

            <a href="login">
            <i class="bi bi-person-plus"></i>
                <span>Join Us</span>
            </a>
    <?php endif; ?>
    </nav>

    <nav class="navBar navBar-time">
        <div class="time-display">
        <i class="bi bi-clock"></i>
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
                <button type="submit" class="time-btn time-btn-reset">   <i class="bi bi-arrow-clockwise"></i> Reset</button>
            </form>
        </div>
    </nav>


    




</body>
</html>