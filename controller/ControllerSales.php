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
            
           
            $mainPicture = ItemPicture::get_main_picture($item->id);
            
         
            $pictures = $item->get_pictures();
            $pictureCount = count($pictures);
            
            
            $winnerPseudo = $item->get_highest_bidder_pseudo();
            
           
            $sellerPseudo = $currentUser->get_Pseudo();
            
            $sold_items[] = [
                'id' => $item->id,
                'title' => $item->title,
                'pic_path' => $mainPicture ? $mainPicture->picture_path : null,
                'picture_count' => $pictureCount,
                'seller_pseudo' => $sellerPseudo,
                'buy_now_price' => $item->buy_now_price,
                'starting_bid' => $item->starting_bid,
                'max_bid' => $item->max_bid,
                'final_price' => $item->max_bid, 
                'end_at' => $item->end_at,
                'is_auction' => $item->is_auction,
                'has_buy_now' => $item->has_buy_now,
                'is_direct_sale' => $item->is_direct_sale,
                'winner_pseudo' => $winnerPseudo,
                'closed_at' => $item->end_at 
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