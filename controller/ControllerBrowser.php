<?php
require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'utils/AppTime.php';
require_once 'model/Item.php';
require_once 'model/ItemPicture.php';
require_once 'model/User.php';



class ControllerBrowser extends Controller {

    public function index(): void {
        $currentUser = $this->get_user_or_false();
        $currentUserId = $currentUser ? $currentUser->get_Id() : null;
        $now = AppTime::get_current_datetime();

        $participating_items_raw = Item::get_Item_Participating($currentUserId,$now);
        $available_items_raw = Item::get_Item_Available($currentUserId, $now);
    

        $participating_items = [];
        foreach ($participating_items_raw as $item) {
            // S'assurer que $item est bien un objet Item
            if (!$item instanceof Item)
                continue;

            $mainPicture = ItemPicture::get_main_picture($item->get_Id());

            // Récupérer le pseudo du vendeur
            $sellerPseudo = Item::get_User_Pseudo_By_Id($item->get_owner());

            $participating_items[] = [
                'id' => $item->get_Id(),
                'title' => $item->get_Title(),
                'pic_path' => $mainPicture?->picture_path,
                'picture_count' => 0, // Set to 0 to avoid view errors
                'seller_pseudo' => $sellerPseudo,
                'buy_now_price' => $item->get_Buy_Now_Price(),
                'starting_bid' => $item->get_Starting_Bid(),
                'max_bid' => $item->get_max_bid_time(),
                'end_at' => $item->get_End_At(),
                'time_remaining' => $this->calculate_time_remaining($item->get_End_At()),
                'is_auction' => $item->get_Is_Auction(),
                'has_buy_now' => $item->get_Has_buy_now_price(),
                'is_highest_bidder' => Item::is_Highest_Bidder($currentUserId, $item->get_Id()),
                'has_bid' => Item::has_Bid_On_Item($currentUserId, $item->get_Id()),
                'is_owner' => $item->get_owner(),
                'description' => $item->get_Description()
            ];
        }

        // Préparer les données pour la vue - Items disponibles
        $available_items = [];
        foreach ($available_items_raw as $item) {
            // S'assurer que $item est bien un objet Item
            if (!$item instanceof Item)
                continue;

            $mainPicture = ItemPicture::get_main_picture($item->get_Id());

            // Récupérer le pseudo du vendeur
            $sellerPseudo = Item::get_User_Pseudo_By_Id($item->get_owner());

            $available_items[] = [
                'id' => $item->get_Id(),
                'title' => $item->get_Title(),
                'pic_path' => $mainPicture?->picture_path,
                'picture_count' => 0, // Set to 0 to avoid view errors
                'seller_pseudo' => $sellerPseudo,
                'buy_now_price' => $item->get_Buy_Now_Price(),
                'starting_bid' => $item->get_Starting_Bid(),
                'max_bid' => $item->get_max_bid_time(),
                'end_at' => $item->get_End_At(),
                'time_remaining' => $this->calculate_time_remaining($item->get_End_At()),
                'is_auction' => $item->get_Is_Auction(),
                'has_buy_now' => $item->get_Has_buy_now_price(),
                'is_direct_sale' => $item->get_Is_Direct_Sale(),
                'is_owner' => $item->get_owner(),
                'description' => $item->get_Description()
            ];
        }
        
        
        (new View("browser"))->show([
            'participating_items' => $participating_items,
            'available_items' => $available_items,
            'current_user_id' => $currentUserId,
            'currentUser' => $currentUser,
            'header_title' => 'Browser',
            'header_icon' => 'bi-cart-fill'
        ]);

    } 
    // Calcule le temps restant jusqu'à end_at
    private function calculate_time_remaining(string $end_at): string
    {
        $now = new DateTime(AppTime::get_current_datetime());
        $end = new DateTime($end_at);

        if ($end <= $now) {
            return "0d 0h";
        }

        $diff = $now->diff($end);

        $days = $diff->days;
        $hours = $diff->h;

        return $days . "d " . $hours . "h";
    } 

}