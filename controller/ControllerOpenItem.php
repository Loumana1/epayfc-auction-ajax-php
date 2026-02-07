<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/Bid.php";
require_once "model/User.php";
require_once "utils/AppTime.php";

class ControllerOpenItem extends Controller {

    
    public function index(): void {


        $item_id = $_GET['param1'] ?? null;
 
        if (!$item_id || !ctype_digit($item_id)) {
            throw new Exception("Invalid item ID: Item does not exist --> '$item_id'");
        }


        $item = Item::get_by_id((int)$item_id);
        if ($item === false) {
            throw new Exception("Item #$item_id not found.");
        }



        $current_user = $this->get_user_or_false();
        $current_user_id = $current_user ? $current_user->get_Id() : null;
        $is_owner = $current_user_id && $item->get_owner() == $current_user_id;
        $is_open = $item->is_open();  

        $is_highest_bidder = false;
        if ($current_user_id) {
            $is_highest_bidder = Bid::is_user_highest($current_user_id, $item_id);
        }
     
        
        // -------REDIRECTION SI PAS AUTORISE---------
        if (!$is_open && !$is_owner && !$is_highest_bidder) {
            (new View("error"))->show(['error' => "This item is only available to the owner or the winner."]);
            return;
        }
        
  
        // ---------------------------------
        $has_bids_time = $item->has_bids_time();
        $max_bid_time = $item->get_max_bid_time();
        $buy_now_reached_time = $item->has_buy_now_reached_time();
        $is_sold = $has_bids_time || $buy_now_reached_time;
        $highest_bidder_pseudo = $item->get_highest_bidder_pseudo();
        $min_bid_amount = $item->get_min_bid_amount();

    
        // -------------- BOUTONS -------------------
        $show_buttons = true;
        $buttons_disabled = false;

        if (!$is_open) {
            $show_buttons = false;
        } elseif ($is_owner || !$current_user) {
      
            $buttons_disabled = true;
        }


        // -------------- MESSAGES -------------
        $status_message = '';


        if ($is_open) {
    
            if ($is_owner) {
                $status_message = "You cannot bid on your own listing.";

            } elseif (!$current_user) {
                $status_message = "Please log in";

            }
            
        } else {
    
            if ($is_highest_bidder) {
                $final_price = $max_bid_time ?? 0;
                $status_message = "Congratulations! You purchased this item for € " . number_format($final_price, 2, ',', '.');
   
            } elseif ($is_owner) {
                if ($is_sold) {
                    $status_message = $highest_bidder_pseudo . " won this item for € " . number_format($max_bid_time, 2, ',', '.');
                    
                } else {
                    $status_message = "This listing ended without a buyer.";
                    
                }
            }
        }


//-------------------------------------
        $bids = $item->get_bids();
        $pictures = $item->get_pictures();
        $selected_img = isset($_GET['param2']) && $_GET['param2'] !== '' ? (int) $_GET['param2'] : 0;
        if ($selected_img < 0 || (count($pictures) > 0 && $selected_img >= count($pictures))) {
            $selected_img = 0;
        }
        $main_picture_path = null;
        if (!empty($pictures)) {
            if ($selected_img === 0) {
                $mainPic = $item->get_main_picture();
                $main_picture_path = $mainPic !== null ? $mainPic->picture_path : $pictures[0]['picture_path'];
            } else {
                $main_picture_path = $pictures[$selected_img]['picture_path'] ?? $pictures[0]['picture_path'];
            }
        }
        $seller = $item->get_seller();

        $has_active_bids = $has_bids_time;
        $item_purchased = !$is_open && $is_sold;



        $data = [
            'header_title' => 'Item open',
            'header_icon' => 'bi-cart-fill',
            'back_url' => 'browser',
            'item' => $item,
            'item_id' => $item_id,
            'pictures' => $pictures,
            'selected_img' => (int) $selected_img,
            'main_picture_path' => $main_picture_path,
            'seller' => $seller,
            'bids' => $bids,
            'is_open' => $is_open,
            'is_owner' => $is_owner,
            'current_user' => $current_user,
            'is_highest_bidder' => $is_highest_bidder,
            'max_bid_time' => $max_bid_time,
            'min_bid_amount' => $min_bid_amount,
            'has_bids_time' => $has_bids_time,
            'show_buttons' => $show_buttons,
            'buttons_disabled' => $buttons_disabled,
            'status_message' => $status_message,
            'has_active_bids' => $has_active_bids,
            'item_purchased' => $item_purchased,
            'show_bid_history' => $item->get_Is_Auction(),
            'auction_ended' => !$is_open && $item->get_Is_Auction(),
        ];


            (new View("open_item"))->show($data);
        }
}
