<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/Bid.php";
require_once "model/User.php";
require_once "utils/AppTime.php";

class ControllerOpenItem extends Controller {

    
    public function index(): void {


        $itemId = $_GET['param1'] ?? null;
 
        if (!$itemId || !ctype_digit($itemId)) {
            throw new Exception("Invalid item ID: '$itemId'");
        }


        $item = Item::get_by_id((int)$itemId);
        if ($item === false) {
            throw new Exception("Item #$itemId not found.");
        }



        $currentUser = $this->get_user_or_false();
        $currentUserId = $currentUser ? $currentUser->get_Id() : null;
        $isOwner = $currentUserId && $item->get_owner() == $currentUserId;
        $isOpen = $item->is_open();  

        $bidErrors = [];
        $bidAmount = null;
        if (isset($_SESSION['bid_errors'])) {
            $bidErrors = $_SESSION['bid_errors'];
            $bidAmount = $_SESSION['bid_amount'] ?? null;
            unset($_SESSION['bid_errors']); // Supprimer après lecture
            unset($_SESSION['bid_amount']);
        }
        

        $isHighestBidder = false;
        if ($currentUserId) {
            $isHighestBidder = Bid::is_user_highest($currentUserId, $itemId);
        }

        
        // -------REDIRECTION SI PAS AUTORISE---------
        if (!$isOpen && !$isOwner && !$isHighestBidder) {
            (new View("error"))->show(['error' => "Invalid item ID."]);
            return;
        }
        
  
        // ---------------------------------
        $hasBidsTime = $item->has_bids_time();
        $maxBidTime = $item->get_max_bid_time();
        $buyNowReachedTime = $item->has_buy_now_reached_time();
        $isSold = $hasBidsTime || $buyNowReachedTime;
        $highestBidderPseudo = $item->get_highest_bidder_pseudo();
        $minBidAmount = $item->get_min_bid_amount();

    
        // -------------- BOUTONS -------------------
        $showButtons = true;
        $buttonsDisabled = false;

        if (!$isOpen) {
            $showButtons = false;
        } elseif ($isOwner || !$currentUser) {
      
            $buttonsDisabled = true;
        }


        // -------------- MESSAGES -------------
        $statusMessage = '';


        if ($isOpen) {
    
            if ($isOwner) {
                $statusMessage = "You cannot bid on your own listing.";

            } elseif (!$currentUser) {
                $statusMessage = "Please log in";

            }
            
        } else {
    
            if ($isHighestBidder) {
                $finalPrice = $maxBidTime ?? 0;
                $statusMessage = "Congratulations! You purchased this item for € " . number_format($finalPrice, 2, ',', '.');
   
            } elseif ($isOwner) {
                if ($isSold) {
                    $statusMessage = $highestBidderPseudo . " won this item for € " . number_format($maxBidTime, 2, ',', '.');
                    
                } else {
                    $statusMessage = "This listing ended without a buyer.";
                    
                }
            }
        }


//-------------------------------------
        $bids = $item->get_bids();
        $pictures = $item->get_pictures();
        $selectedImg = isset($_GET['param2']) && $_GET['param2'] !== '' ? (int)$_GET['param2'] : 0;
        if ($selectedImg < 0 || (count($pictures) > 0 && $selectedImg >= count($pictures))) {
            $selectedImg = 0;
        }
        $seller = $item->get_seller();

        $hasActiveBids = $hasBidsTime;
        $itemPurchased = !$isOpen && $isSold;



        $data = [
            'bidErrors' => $bidErrors,
            'bidAmount' => $bidAmount,
            'header_title' => 'Item open',
            'header_icon' => 'bi-cart-fill',
            'back_url' => 'browser',
            'item' => $item,
            'itemId' => $itemId,
            'pictures' => $pictures,
            'selectedImg' => $selectedImg,
            'seller' => $seller,
            'bids' => $bids,
            'isOpen' => $isOpen,
            'isOwner' => $isOwner,
            'currentUser' => $currentUser,
            'isHighestBidder' => $isHighestBidder,
            'maxBidTime' => $maxBidTime,
            'minBidAmount' => $minBidAmount,
            'hasBidsTime' => $hasBidsTime,
            'showButtons' => $showButtons,
            'buttonsDisabled' => $buttonsDisabled,
            'statusMessage' => $statusMessage,
            'hasActiveBids' => $hasActiveBids,
            'itemPurchased' => $itemPurchased,
            'showBidHistory' => $item->get_Is_Auction(),
        ];


            (new View("open_item"))->show($data);
        }
}
