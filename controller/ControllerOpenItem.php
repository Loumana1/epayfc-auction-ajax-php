<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/Item.php";
require_once "model/Bid.php";
require_once "model/User.php";
require_once "utils/AppTime.php";
require_once "utils/format.php";

class ControllerOpenItem extends Controller {

    
    public function index(): void {

        $item = $this->load_item_or_fail();

         if ($item === null) return;
        $context = $this->get_user_context($item);
        
        if (!$this->check_access($item, $context)) {
            return;
        }
        
        $data = $this->prepare_view_data($item, $context);
        (new View("open_item"))->show($data);
    }
    
    // ============ VALIDATION ============
    
private function load_item_or_fail(): ?Item {
    $item_id = $_GET['param1'] ?? null;
    
    if (!$item_id || !ctype_digit($item_id)) {
        $this->show_error("Invalid url : '$item_id'", "Invalid Request");
        return null;
    }
    
    $item = Item::get_by_id((int)$item_id);
    if ($item === false) {
        $this->show_error("Item #$item_id not found.", "Item Not Found");
        return null;
    }
    
    return $item;
}
    
    // ============ CONTEXTE UTILISATEUR ============
    
    private function get_user_context(Item $item): array {
        $current_user = $this->get_user_or_false();
        $current_user_id = $current_user ? $current_user->get_Id() : null;
        $is_open = $item->is_open();
        
        $is_owner = $current_user_id && $item->get_owner() == $current_user_id;
        $is_highest_bidder = $current_user_id 
            ? Bid::is_user_highest($current_user_id, $item->get_Id()) 
            : false;
        
        // État de l'item
        $has_bids_time = $item->has_bids_time();
        $max_bid_time = $item->get_max_bid_time();
        $is_sold = $has_bids_time || $item->has_buy_now_reached_time();
        
        return [
            'current_user' => $current_user,
            'current_user_id' => $current_user_id,
            'is_owner' => $is_owner,
            'is_open' => $is_open,
            'is_highest_bidder' => $is_highest_bidder,
            'has_bids_time' => $has_bids_time,
            'max_bid_time' => $max_bid_time,
            'is_sold' => $is_sold,
        ];
    }
    
    // ============ CONTRÔLE ACCES ============
    
    private function check_access(Item $item, array $context): bool {
        if (!$context['is_open'] && !$context['is_owner'] && !$context['is_highest_bidder']) {
            (new View("error"))->show([
                'error' => "This item is only available to the owner or the winner.",
                'header_title' => 'Access denied',
                    'page_css' => ['error.css'],
            ]);
            return false;
        }
        return true;
    }
    
    // ========== ETAT DES BOUTONS ============
    
    private function get_button_state(array $context): array {
        $show_buttons = true;
        $buttons_disabled = false;
        
        if (!$context['is_open']) {
            $show_buttons = false;
        } elseif ($context['is_owner'] || !$context['current_user']) {
            $buttons_disabled = true;
        }
        
        return [
            'show_buttons' => $show_buttons,
            'buttons_disabled' => $buttons_disabled,
        ];
    }
    
    // ============ MESSAGE DE STATUT ===============
    
    private function get_status_message(Item $item, array $context): string {
        if ($context['is_open']) {
            if ($context['is_owner']) {
                return "You cannot bid on your own listing.";
            }
            if (!$context['current_user']) {
                return "Please log in";
            }
            return '';
        }
        
        // Item fermé
        if ($context['is_highest_bidder']) {
            $final_price = $context['max_bid_time'] ?? 0;
            return "Congratulations! You purchased this item for " . format_euro($final_price);
        }
        
        if ($context['is_owner']) {
            if ($context['is_sold']) {
                $pseudo = $item->get_highest_bidder_pseudo();
                return "$pseudo won this item for " . format_euro($context['max_bid_time']);
            }
            return "This listing ended without a buyer.";
        }
        
        return '';
    }
    
    // ======== DONNEES IMAGES ============
    
    private function get_picture_data(Item $item): array {
        $pictures = $item->get_pictures();
        $selected_img = isset($_GET['param2']) && $_GET['param2'] !== '' 
            ? (int)$_GET['param2'] 
            : 0;
        
        if ($selected_img < 0 || (count($pictures) > 0 && $selected_img >= count($pictures))) {
            $selected_img = 0;
        }
        
        $main_picture_path = null;
        if (!empty($pictures)) {
            if ($selected_img === 0) {
                $main_pic = $item->get_main_picture();
                $main_picture_path = $main_pic !== null 
                    ? $main_pic->picture_path 
                    : $pictures[0]['picture_path'];
            } else {
                $main_picture_path = $pictures[$selected_img]['picture_path'] 
                    ?? $pictures[0]['picture_path'];
            }
        }
        
        return [
            'pictures' => $pictures,
            'selected_img' => $selected_img,
            'main_picture_path' => $main_picture_path,
        ];
    }
    
    // ============== POUR VUE ============
    
    private function prepare_view_data(Item $item, array $context): array {
        $button_state = $this->get_button_state($context);
        $picture_data = $this->get_picture_data($item);
        $status_message = $this->get_status_message($item, $context);
        

            // Déterminer l'URL de retour
        $from = $_GET['param3'] ?? null;
        $back_url = match($from) {
            'sales' => 'sales',
            'my_items' => 'my_items',
            'purchases' => 'purchases',
            default => 'browser'
        };
        $is_open = $context['is_open'];
        $has_bids_time = $context['has_bids_time'];
        $is_sold = $context['is_sold'];
        
        return [
            'header_title' => 'Item open',
            'header_icon' => 'bi-cart-fill',
            'back_url' => $back_url,
            'item' => $item,
            'item_id' => $item->get_Id(),
            'pictures' => $picture_data['pictures'],
            'selected_img' => $picture_data['selected_img'],
            'main_picture_path' => $picture_data['main_picture_path'],
            'seller' => $item->get_seller(),
            'bids' => $item->get_bids(),
            'is_open' => $is_open,
            'is_owner' => $context['is_owner'],
            'current_user' => $context['current_user'],
            'is_highest_bidder' => $context['is_highest_bidder'],
            'max_bid_time' => $context['max_bid_time'],
            'min_bid_amount' => $item->get_min_bid_amount(),
            'has_bids_time' => $has_bids_time,
            'show_buttons' => $button_state['show_buttons'],
            'buttons_disabled' => $button_state['buttons_disabled'],
            'status_message' => $status_message,
            'has_active_bids' => $has_bids_time,
            'item_purchased' => !$is_open && $is_sold,
            'show_bid_history' => $item->get_Is_Auction(),
            'auction_ended' => !$is_open && $item->get_Is_Auction(),
            'page_css' => ['open_item.css'],
        ];
    }
}
