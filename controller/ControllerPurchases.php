<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "utils/AppTime.php";
require_once "model/ItemPicture.php";



class ControllerPurchases extends Controller {

    public function index(): void {

        // sécurité
        $user = $this->get_user_or_redirect();
        $userId = $user->get_id();
        $now = AppTime::get_current_datetime();

        // items gagnés par l'utilisateur
        $items = Item::get_Item_Participating($userId, $now);

        // stats
        $total = 0;
        $count = count($items);
        $sellers = [];

        foreach ($items as $item) {
            $price = $item->get_max_bid_time();
            if ($price) {
                $total += $price;
            }
            $seller = $item->get_seller()->get_pseudo();
            $sellers[$seller] = ($sellers[$seller] ?? 0) + 1;
        }

        arsort($sellers);
        $topSeller = array_key_first($sellers);

        $stats = [
            "count" => $count,
            "total" => $total,
            "average" => $count > 0 ? $total / $count : 0,
            "top_seller" => $topSeller
        ];

        (new View("purchases"))->show([
            "items" => $items,
            "stats" => $stats
        ]);
    }
}
