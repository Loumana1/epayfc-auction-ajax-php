<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/ItemPicture.php";
require_once "utils/AppTime.php";


class ControllerPurchases extends Controller {

    public function index(): void {

        $user = $this->get_user_or_redirect();
        $userid = $user->get_id();
        $now = AppTime::get_current_datetime();

        
        $items = Item::get_purchased_items_by_user($userid, $now);

        
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
        $top_seller = array_key_first($sellers);

        $stats = [
            "count" => $count,
            "total" => $total,
            "average" => $count > 0 ? $total / $count : 0,
            "top_seller" => $top_seller
        ];

        (new View("purchases"))->show([
            "items" => $items,
            "stats" => $stats,
            "currentUser" => $user 
        ]);
    }
}

