<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/ModelItems.php";
require_once "utils/AppTime.php";

class ControllerOpenItem extends Controller {

    
    public function index(): void {

        // Debug
        /*
        echo "<pre>DEBUG _GET: ";
       print_r($_GET);
        echo "</pre>";
        */
        $itemId = $_GET['param1'] ?? null;

        // Verifier id item recu en url 
        // cas : 
        // - contient rien
        // - pas un chiffre 
        if (!$itemId || !is_numeric($itemId)) {
            // pour tester
            //remplacer ^par show browser
            throw new Exception("ERREUR: Pas d'ID d'item fourni" );

        }
        
        $itemId = (int)$itemId;
        
        // Lier id a un id de la db sinon vide 
        $item = ModelItems::get_Item_By_Id($itemId);

        // Verifier si id correspond a un item de la db
        //ne doit pas etre vide --> sinon retour browser 
        if ($item === false) {
            // pour tester
            //remplacer ^par show browser
              throw new Exception("ERREUR: Item avec ID $itemId n'existe pas");
        }


        $currentUser = $this->get_user_or_false();

        
        //  item pics
        $pictures = ModelItems::get_Item_Pictures($itemId);
        
        
        // venduer seller info
        $seller = User::get_User_By_Id($item->get_is_owner());

               // verifier si Enchere tjrs ouvert
        $now = AppTime::get_current_datetime();
        $endAt = $item->get_End_At();
        
        if ($endAt) { 
            $endAtDateTime = new DateTime($endAt);
            $nowDateTime = new DateTime($now);
            $isOpen = $endAtDateTime > $nowDateTime && !$item->buy_now_reached;
        } else {
            $isOpen = false;
        }
//toute les donnés a utiliser dans la vue 
    $data = [
        'item' => $item,
        'pictures' => $pictures,
        'seller' => $seller,
        'isOpen' => $isOpen,
        'currentUser' => $currentUser
  
    ];


        (new View("open_item"))->show($data);
    }
}