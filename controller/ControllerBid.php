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

            $current_user = $this->get_user_or_false();
            if (!$current_user) {
                $this->redirect("login");
                return;
            }
            

            $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
            $amount_str = isset($_POST['amount']) ? $_POST['amount'] : null;


            if ($amount_str) {
                $amount_str = str_replace(',', '.', $amount_str);
                $amount = (float)$amount_str;
            } else {
                $amount = null;
            }
            
            if (!$item_id || !$amount) {
                $this->redirect("open_item", "index", $item_id);
                return;
            }
            //CREER VERIFICATION POUR SPAM CLICK PLACE BID
            //ACTUELLEMENT ERREUR PARCE 2 FOIS LA MEME REQUETE SQL
            //MEME CLEF PRIMAAIRE APAREMENT
            //TRY CATCH ERROR A CREER 
            
        
            $item = Item::get_by_id($item_id);
            if ($item === false) {
                $this->redirect("open_item", "index", $item_id);
                return;
            }
        
        $bid = new Bid($item_id, $current_user->get_id(), $amount);
        $errors = $bid->persist();

       
        $this->redirect("open_item", "index", $item_id);
    }

}