<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "utils/AppTime.php";

class ControllerPurchases extends Controller {

    public function index(): void {

        $user = $this->get_user_or_redirect();
        $userId = $user->get_id();
        $now = AppTime::get_current_datetime();

        $purchases = Item::get_purchases_by_user($userId, $now);
        $stats = Item::get_purchase_statistics($userId, $now);

        (new View("purchases"))->show([
            "items" => $purchases,
            "stats" => $stats
        ]);
    }
}
