<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/ModelItems.php";
require_once "utils/AppTime.php";

class ControllerOpenItem extends Controller {

    
    public function index(): void {


        $itemId = $_GET['param1'] ?? null;
        // Verifier id item recu en url 
        // cas : 
        if (!$itemId || !is_numeric($itemId)) {
            throw new Exception("ERREUR: Pas d'ID d'item fourni" );

        }
        
        $itemId = (int)$itemId;
        
        // Lier id a un id de la db sinon vide 
        $item = ModelItems::get_Item_By_Id($itemId);

      
        if ($item === false) {
              throw new Exception("ERREUR: Item avec ID $itemId n'existe pas");
        }

            $currentUser = $this->get_user_or_false();
        $currentUserId = $currentUser ? $currentUser->get_Id() : null;
        $isOwner = $currentUserId && $item->get_Owner() == $currentUserId;
                

     
        
      
        $bids = ModelItems::get_Item_Bids($itemId); 
        $pictures = ModelItems::get_Item_Pictures($itemId);
         //  item pics
         $selectedImg = isset($_GET['param2']) && $_GET['param2'] !== '' ? (int)$_GET['param2'] : 0;
   

        // Valider  $selectedImg
        if ($selectedImg < 0 || (count($pictures) > 0 && $selectedImg >= count($pictures))) {
            $selectedImg = 0;
}
        
        // venduer seller info
        $seller = $seller = User::get_User_By_Id($item->get_Owner());

               // verifier si Enchere tjrs ouvert
        $now = AppTime::get_current_datetime();
        $endAt = $item->get_End_At();
       // A verififier  
        if ($endAt) { 
            $endAtDateTime = new DateTime($endAt);
            $nowDateTime = new DateTime($now);
            $isOpen = $endAtDateTime > $nowDateTime && !$item->buy_now_reached;
        } else {
            $isOpen = false;
        }

            // Verifier statut des enchere pour l'utilistuer courant 
            $UserBidStatus = false; 
            $isHighestBidder = false;
            if ($currentUserId) {
                $UserBidStatus = ModelItems::has_User_Bid_On_Item($currentUserId, $itemId);
                $isHighestBidder = ModelItems::is_User_Highest_Bidder($currentUserId, $itemId);
            }

        $minBidAmount = $item->get_Max_Bid()
            ? $item->get_Max_Bid() + 0.01
            : ($item->get_Starting_Bid() ?: 0);



//toute les donnés a utiliser dans la vue 
    $data = [
        'selectedImg' => $selectedImg,
        'item' => $item,
        'itemId' => $itemId,
        'pictures' => $pictures,
        'seller' => $seller,
        'isOpen' => $isOpen,
        'currentUser' => $currentUser,
            'UserBidStatus' => $UserBidStatus,
        'isHighestBidder' => $isHighestBidder,
         'now'=>$now,
          'minBidAmount' => $minBidAmount,
          'bids' => $bids,
          'isOwner' => $isOwner
  
    ];


        (new View("open_item"))->show($data);
    }
}