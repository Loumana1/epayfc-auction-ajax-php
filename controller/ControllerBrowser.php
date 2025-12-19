<?php

require_once "framework/Controller.php";
require_once 'framework/View.php';
require_once 'utils/AppTime.php';
require_once 'model/ModelItems.php';
require_once 'model/ItemPicture.php';


class ControllerBrowser extends Controller {

    public function index(): void {
        $userId = 4 ; // apres corrige
        $now = AppTime::get_current_datetime();

        $participating_items_raw = ModelItems::get_Item_Participating($userId,$now);
        $available_items_raw = ModelItems::get_Item_Available($userId, $now);
    

        $participating_items = [];
        foreach ($participating_items_raw as $item) {
            // S'assurer que $item est bien un objet Item
            if (!$item instanceof Item)
                continue;

            $mainPicture = ItemPicture::get_main_picture($item->id);

            // Récupérer le pseudo du vendeur
            $sellerPseudo = ModelItems::get_User_Pseudo_By_Id($item->is_owner);

            $participating_items[] = [
                'id' => $item->id,
                'title' => $item->title,
                'pic_path' => $mainPicture?->picture_path,
                'picture_count' => 0, // Set to 0 to avoid view errors
                'seller_pseudo' => $sellerPseudo,
                'buy_now_price' => $item->buy_now_price,
                'starting_bid' => $item->starting_bid,
                'max_bid' => $item->max_bid,
                'end_at' => $item->end_at,
                'time_remaining' => $this->calculate_time_remaining($item->end_at),
                'is_auction' => $item->is_auction,
                'has_buy_now' => $item->has_buy_now,
                'is_highest_bidder' => ModelItems::is_Highest_Bidder($userId, $item->id),
                'has_bid' => ModelItems::has_Bid_On_Item($userId, $item->id),
                'is_owner' => $item->is_owner,
                'description' => $item->description
            ];
        }

        // Préparer les données pour la vue - Items disponibles
        $available_items = [];
        foreach ($available_items_raw as $item) {
            // S'assurer que $item est bien un objet Item
            if (!$item instanceof Item)
                continue;

            $mainPicture = ItemPicture::get_main_picture($item->id);

            // Récupérer le pseudo du vendeur
            $sellerPseudo = ModelItems::get_User_Pseudo_By_Id($item->is_owner);

            $available_items[] = [
                'id' => $item->id,
                'title' => $item->title,
                'pic_path' => $mainPicture?->picture_path,
                'picture_count' => 0, // Set to 0 to avoid view errors
                'seller_pseudo' => $sellerPseudo,
                'buy_now_price' => $item->buy_now_price,
                'starting_bid' => $item->starting_bid,
                'max_bid' => $item->max_bid,
                'end_at' => $item->end_at,
                'time_remaining' => $this->calculate_time_remaining($item->end_at),
                'is_auction' => $item->is_auction,
                'has_buy_now' => $item->has_buy_now,
                'is_direct_sale' => $item->is_direct_sale,
                'is_owner' => $item->is_owner,
                'description' => $item->description
            ];
        }
        
        
        (new View("browser"))->show([
            'participating_items' => $participating_items,
            'available_items' => $available_items,
            'current_user_id' => $userId
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