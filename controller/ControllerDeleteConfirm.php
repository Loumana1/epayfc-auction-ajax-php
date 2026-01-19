<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once  "model/Item.php";
require_once "model/User.php";

class ControllerDeleteConfirm extends Controller {

    public function index(): void {
        $user = $this->get_user_or_redirect();
        
        $itemId = $_GET['param1'] ?? null;
        
        if (!$itemId || !is_numeric($itemId)) {
            $this->redirect('my_items');
            return;
        }
        
        $item = item::get_By_Id((int)$itemId);
        
        if (!$item) {
            $this->redirect('my_items');
            return;
        }
        
    
        if ($item->get_Owner() != $user->get_Id()) {
            $this->redirect('browse_items');
            return;
        }
        
   
        if ($item->has_bids) {
            $this->redirect('open_item', 'index', (string)$itemId);
            return;
        }
        $seller = User::get_User_By_Id($item->get_Owner());
        
        (new View("delete_confirm"))->show([
            'item' => $item,
            'currentUser' => $user,
            'seller' => $seller 
        ]);
    }
    
    public function confirm(): void {
        $user = $this->get_user_or_redirect();
        
        $itemId = $_POST['item_id'] ?? null;
        
        if (!$itemId) {
            $this->redirect('my_items');
            return;
        }
        
        $item = Item::get_By_Id((int)$itemId);


        if (!$item || $item->get_owner() != $user->get_Id() || $item->has_bids_time()) {
            $this->redirect('my_items');
            return;
        }
        
        //cascade
        Item::delete_pictures((int)$itemId);
        
        $this->redirect('my_items');
    }
}