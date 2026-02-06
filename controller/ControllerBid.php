<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/Bid.php";
require_once "model/User.php"; 
require_once "utils/AppTime.php";

class ControllerBid extends Controller {


    // Si quelqu'un accède à bid sans action, rediriger
    public function index(): void {
        $this->redirect("browse_items");
    }





    public function create(): void {

            $currentUser = $this->get_user_or_false();
            if (!$currentUser) {
                $this->redirect("login");
                return;
            }
            

            $itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
            $amountStr = isset($_POST['amount']) ? $_POST['amount'] : null;


            if ($amountStr) {
                $amountStr = str_replace(',', '.', $amountStr);
                $amount = (float)$amountStr;
            } else {
                $amount = null;
            }
            
            if (!$itemId || !$amount) {
                $this->redirect("open_item", "index", $itemId);
                return;
            }
            //CREER VERIFICATION POUR SPAM CLICK PLACE BID
            //ACTUELLEMENT ERREUR PARCE 2 FOIS LA MEME REQUETE SQL
            //MEME CLEF PRIMAAIRE APAREMENT
            //TRY CATCH ERROR A CREER 
            
        
            $item = Item::get_by_id($itemId);
            if ($item === false) {
                $this->redirect("open_item", "index", $itemId);
                return;
            }
        
        $bid = new Bid($itemId, $currentUser->get_id(), $amount);
        $errors = $bid->persist();

       
        $this->redirect("open_item", "index", $itemId);
    }

    public function ack(): void {
        $itemId = isset($_GET['param1']) ? $_GET['param1'] : null;
       
        if (!$itemId) {
            $this->redirect("browser");
            return;
        }
       
        $this->redirect("open_item", "index", (string)$itemId);
    }
}