<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/ItemPicture.php";
require_once "model/User.php";  
require_once "utils/AppTime.php";

class ControllerSales extends Controller {

    public function index(): void {

        $currentUser = $this->get_user_or_redirect();
        $userId = $currentUser->get_Id();
        
        
        $now = AppTime::get_current_datetime();
        
        
        $statistics = Item::get_sales_statistics($userId, $now);
        
      
        $sold_items_raw = Item::get_sold_items_by_owner($userId, $now);
        
    
        $sold_items = [];
        foreach ($sold_items_raw as $item) {
            if (!$item instanceof Item) continue;
            
           
            $mainPicture = ItemPicture::get_main_picture($item->get_Id());
            
         
            $pictures = $item->get_pictures();
            $pictureCount = count($pictures);
            
            
            $winnerPseudo = $item->get_highest_bidder_pseudo();
            
           
            $sellerPseudo = $currentUser->get_Pseudo();
            
            $sold_items[] = [
                'id' => $item->get_Id(),
                'title' => $item->get_Title(),
                'pic_path' => $mainPicture ? $mainPicture->picture_path : null,
                'picture_count' => $pictureCount,
                'seller_pseudo' => $sellerPseudo,
                'buy_now_price' => $item->get_Buy_Now_Price(),
                'starting_bid' => $item->get_Starting_Bid(),
                'max_bid' => $item->get_max_bid_time(),
                'final_price' => $item->get_max_bid_time(), 
                'end_at' => $item->get_End_At(),
                'is_auction' => $item->get_Is_Auction(),
                'has_buy_now' => $item->get_Has_buy_now_price(),
                'is_direct_sale' => $item->get_Is_Direct_Sale(),
                'winner_pseudo' => $winnerPseudo,
                'closed_at' => $item->get_End_At() 
            ];
        }
        
        (new View("sales"))->show([
            'header_title' => 'Sales',
            'header_icon' => 'bi-cart',
            'back_url' => 'profile',
            'sold_items' => $sold_items,
            'statistics' => $statistics,
            'currentUser' => $currentUser,
            'now' => $now
        ]);
    }
}