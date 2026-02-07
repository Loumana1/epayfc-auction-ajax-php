<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once  "model/Item.php";
require_once "model/User.php";

class ControllerDeleteConfirm extends Controller {

    public function index(): void {
        $user = $this->get_user_or_redirect();
        
        $item_id = $_GET['param1'] ?? null;
        
        if (!$item_id || !is_numeric($item_id)) {
            $this->redirect('my_items');
            return;
        }
        
        $item = Item::get_by_id((int)$item_id);
        
        if (!$item) {
            $this->redirect('my_items');
            return;
        }
        
    
        if ($item->get_Owner() != $user->get_Id()) {
            $this->redirect('browse_items');
            return;
        }
        
   
        if ($item->has_bids_time()) {
            // Plus de message via $_SESSION - redirection simple
            $this->redirect('open_item', 'index', (string)$item_id);
            return;
        }
        $seller = User::get_User_By_Id($item->get_Owner());
        
        (new View("delete_confirm"))->show([
            'header_title' => 'Delete Item',
            'header_icon' => 'bi-trash',
            'back_url' => 'open_item/index/' . $item_id,
            'item' => $item,
            'current_user' => $user,
            'seller' => $seller,
            'page_css' => ['delete_item.css']
        ]);
    }
    
    public function confirm(): void {
        $user = $this->get_user_or_redirect();
        
        $item_id = $_POST['item_id'] ?? null;
        
        if (!$item_id) {
            $this->redirect('my_items');
            return;
        }
        
        $item = Item::get_by_id((int)$item_id);


        if (!$item || $item->get_owner() != $user->get_Id() || $item->has_bids_time()) {
            $this->redirect('my_items');
            return;
        }
        
        //cascade
        $item->delete_pictures();
        $item->delete();

        $this->redirect('my_items');
    }
}