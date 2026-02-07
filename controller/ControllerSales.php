<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/User.php";  
require_once "utils/AppTime.php";
require_once "utils/format.php";

class ControllerSales extends Controller {

    public function index(): void {

        $current_user = $this->get_user_or_redirect();
        $user_id = $current_user->get_Id();
        
        
        $now = AppTime::get_current_datetime();
        
        
        $statistics = Item::get_sales_statistics($user_id, $now);
        
      
        $sold_items = Item::get_sold_items_by_owner($user_id, $now);

        (new View("sales"))->show([
            'header_title' => 'Sales',
            'header_icon' => 'bi-cart',
            'back_url' => 'profile',
            'sold_items' => $sold_items,
            'statistics' => $statistics,
            'current_user' => $current_user,
            'now' => $now,
            'page_css' => ['sales.css']
        ]);
    }
}