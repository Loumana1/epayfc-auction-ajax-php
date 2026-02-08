<?php
require_once "framework/Controller.php";
require_once "framework/Configuration.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/User.php";  
require_once "utils/AppTime.php";
require_once "utils/format.php";

class ControllerSales extends Controller {

    public function index(): void {

        $current_user = $this->get_user_or_redirect();
        $user_id = $current_user->get_Id();
        $web_root = Configuration::get("web_root");
        
        $now = AppTime::get_current_datetime();
        
        
        $statistics = Item::get_sales_statistics($user_id, $now);
        
      
        $sold_items = Item::get_sold_items_by_owner($user_id, $now);

          $sale_cards = [];
            foreach ($sold_items as $item) {
                if (!$item instanceof Item) {
                    continue;
                }
                $sale_cards[] = $this->build_sale_card_data($item, $web_root);
            }

    
            (new View("sales"))->show([
            'header_title' => 'Sales',
            'header_icon' => 'bi-cart',
            'back_url' => 'profile',
            'statistics' => $statistics,
             'sale_cards' => $sale_cards,
            'current_user' => $current_user,
            'now' => $now,
            'page_css' => ['sales.css']
        ]);
}

private function build_sale_card_data(Item $item, string $web_root): array {
    $main_pic = $item->get_main_picture();
    $pic_path = $main_pic ? $main_pic->picture_path : null;
    $closed_at = $item->get_End_At();

    return [
        'item_id' => $item->get_Id(),
        'thumb_url' => $pic_path ? $web_root . str_replace('.jpg', '_thumbnail.jpg', $pic_path) : '',
        'picture_count' => count($item->get_pictures()),
        'title' => $item->get_Title(),
        'seller_pseudo' => $item->get_seller()->get_Pseudo(),
        'display_price' => $item->get_Buy_Now_Price() ?? $item->get_Starting_Bid(),
        'max_bid' => $item->get_max_bid_time(),
        'winner_pseudo' => $item->get_highest_bidder_pseudo(),
        'closed_at_formatted' => $closed_at ? date('d/m/Y H:i', strtotime($closed_at)) : '',
        'is_auction' => $item->get_Is_Auction(),
        'has_buy_now' => $item->get_Has_buy_now_price(),
    ];
}

}