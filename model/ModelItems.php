<?php
require_once "framework/Model.php";
require_once "model/Item.php";
require_once "model/User.php";

class ModelItems extends Model {




    //Choper info de l'item courant
    public static function get_Item_By_Id(int $itemId): Item|false{ 
        $query = self::execute("SELECT * FROM v_items_status WHERE id = :id", ['id' => $itemId]);
        $data = $query->fetch();
        if ($data === false) { 
            return false;
        } else {
            return new Item(
                $data["id"], 
                $data["title"], 
                $data["description"], 
                $data["owner"], 
                $data["created_at"], 
                $data["buy_now_price"], 
                $data["duration_days"], 
                $data["starting_bid"],
                // pour la vue apres a utiliser 
                $data["end_at"] ?? null,
                $data["bid_count"] ?? 0,
                $data["max_bid"] ?? null,
                (bool)($data["is_direct_sale"] ?? false),
                (bool)($data["is_auction"] ?? false),
                (bool)($data["has_buy_now"] ?? false),
                (bool)($data["has_bids"] ?? false),
                (bool)($data["buy_now_reached"] ?? false),
                (bool)($data["not_purchased_direct_sale"] ?? false)
            );
        }
    }





//images
    public static function get_Item_Pictures(int $itemId): array {
        $query = self::execute(
            "SELECT * FROM item_pictures WHERE item = :item_id ORDER BY priority ASC", 
            ['item_id' => $itemId]);
    return $query->fetchAll();//retourne un array de pics
    }






}