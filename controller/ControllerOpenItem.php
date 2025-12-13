<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/ModelItems.php";
require_once "utils/AppTime.php";

class ControllerOpenItem extends Controller {

    
    public function index(): void {

        // Debug
        echo "<pre>DEBUG _GET: ";
       print_r($_GET);
        echo "</pre>";
        
        $itemId = $_GET['param1'] ?? null;

        // Verifier id item recu en url 
        // cas : 
        // - contient rien
        // - pas un chiffre 
        if (!$itemId || !is_numeric($itemId)) {
            // pour tester
            //remplacer ^par show browser
            die("ERREUR: Pas d'ID d'item fourni. param1 reçu: " );
        }
        
        $itemId = (int)$itemId;
        
        // Lier id a un id de la db sinon vide 
        $item = ModelItems::get_Item_By_Id($itemId);

        // Verifier si id correspond a un item de la db
        //ne doit pas etre vide --> sinon retour browser 
        if ($item === false) {
            // pour tester
            //remplacer ^par show browser
            die("ERREUR: Item avec ID $itemId n'existe pas ");
        }



        
        //  item pics
        $pictures = ModelItems::get_Item_Pictures($itemId);
        
        
        // venduer seller info
        $seller = ModelItems::get_User_By_Id($item->get_is_owner());


//toute les donnés a utiliser dans la vue 
    $data = [
        'item' => $item,
        'pictures' => $pictures,
        'seller' => $seller,
 
    ];


        (new View("open_item"))->show($data);
    }
}