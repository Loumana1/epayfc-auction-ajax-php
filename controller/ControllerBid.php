<?php
require_once "framework/Controller.php";
require_once "model/Item.php";
require_once "model/Bid.php";
require_once "model/User.php"; 
require_once "utils/AppTime.php";
require_once "utils/format.php";
class ControllerBid extends Controller {


    // Si quelqu'un accède à bid sans action, rediriger
    public function index(): void {
        $this->redirect("browser");
    }




    public function create(): void {
        $current_user = $this->get_user_or_redirect_login();
        if (!$current_user) return;
        
        $params = $this->extract_bid_params();
        if (!$params) return;
        
        $item = $this->load_item_or_redirect($params['item_id']);
        if (!$item) return;
        
        $this->process_bid($item, $current_user, $params['amount']);
    }
    
    // ============ AUTHENTIFICATION ============
    
    private function get_user_or_redirect_login(): ?object {
        $current_user = $this->get_user_or_false();
        if (!$current_user) {
            $this->redirect("login");
            return null;
        }
        return $current_user;
    }
    
    // ============ EXTRACTION PARAMÈTRES ============
    
    private function extract_bid_params(): ?array {
        $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
        $amount_str = $_POST['amount'] ?? null;
        
        $amount = null;
        if ($amount_str) {
            $amount_str = str_replace(',', '.', $amount_str);
            $amount = (float)$amount_str;
        }
        
        if (!$item_id || !$amount) {
            $this->redirect("open_item", "index", $item_id);
            return null;
        }
        
        return ['item_id' => $item_id, 'amount' => $amount];
    }
    
    // ============ CHARGEMENT ITEM ============
    
    private function load_item_or_redirect(int $item_id): ?Item {
        $item = Item::get_by_id($item_id);
        if ($item === false) {
            $this->redirect("open_item", "index", $item_id);
            return null;
        }
        return $item;
    }
    
    // ============ TRAITEMENT ENCHÈRE ============
    
    private function process_bid(Item $item, object $user, float $amount): void {

        $bid = new Bid($item->get_Id(), $user->get_id(), $amount);
        $errors = $bid->persist($item);

        $is_ajax = ($_POST['format'] ?? '') === 'json';

        if ($is_ajax) {
            header('Content-Type: application/json');
            if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(['success' => false, 'errors' => $errors]);
            } else {
                echo json_encode([
                    'success' => true, 
                    'message' => 'You successfully bidded ' . format_euro($amount)
                ]);
            }
            return;
        }
        
        $this->redirect("open_item", "index", (string)$item->get_Id());


    }

}