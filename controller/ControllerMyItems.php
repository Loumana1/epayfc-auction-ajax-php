<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "utils/AppTime.php";
require_once "model/ItemPicture.php";


class ControllerMyItems extends Controller {

    public function index(): void {

        
        $user = $this->get_user_or_redirect();
        $userid = $user->get_id();

        $now = AppTime::get_current_datetime();

        $items = Item::get_items_by_owner($userid);


        // découpage en 3 catégories
        $active = [];
        $closed_unsold = [];
        $sold = [];

        foreach ($items as $item) {

            if ($item->is_open()) {
                $active[] = $item;
            }
            else if (
                $item->has_bids_time()
                && ($item->has_buy_now_reached_time() || $item->get_end_at() <= $now)
            ) {
                $sold[] = $item;
            }
            else {
                $closed_unsold[] = $item;
            }
        }

        
        (new View("my_items"))->show([
            "active_items" => $active,
            "closed_unsold_items" => $closed_unsold,
            "sold_items" => $sold,
            "currentUser" => $user, 
            "current_page" => "my_items" 
        ]);
    }
}
