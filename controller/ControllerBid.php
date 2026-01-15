<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/ModelItems.php";
require_once "utils/AppTime.php";

class ControllerBid extends Controller {


    // Si quelqu'un accède à /bid sans action, rediriger vers browse_items
    public function index(): void {
        $this->redirect("browse_items");
    }
    //
    // 
    // 
    // auctions
    public function create(): void {
        $currentUser = $this->get_user_or_false();
        
        if (!$currentUser) {
            $this->redirect("login");
            return;
        }

        $itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
        $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : null;

 
        if (!$itemId || !$amount) {
            $this->redirect("open_item", "index", $itemId);
            return;
        }

        // Vérifier que l'item existe et est ouvert
        $item = ModelItems::get_Item_By_Id($itemId);
        if ($item === false) {
            $this->redirect("open_item", "index", $itemId);
            return;
        }

    

        // Créer le bid via le modèle
        $success = ModelItems::create_Bid($currentUser->get_Id(), $itemId, $amount);

        if ($success) {
        
            $this->redirect("open_item", "index", $itemId);
        } else {
            // Gérer l'erreur 
            $this->redirect("open_item", "index", $itemId);
        }
    }

    // Pour Buy Now
    public function create_bid(): void {
        $currentUser = $this->get_user_or_false();
        
        if (!$currentUser) {
            $this->redirect("login");
            return;
        }

        $itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
        $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : null;

    

        // Créer le bid 
        $success = ModelItems::create_Bid($currentUser->get_Id(), $itemId, $amount, true);

        if ($success) {
            $this->redirect("open_item", "index", $itemId);
        } else {
            $this->redirect("open_item", "index", $itemId);
        }
    }
}