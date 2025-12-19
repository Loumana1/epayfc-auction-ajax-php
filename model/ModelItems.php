<?php
require_once "framework/Model.php";
require_once "model/Item.php";
require_once "model/User.php";

class ModelItems extends Model {




    //Choper info de l'item courant
    public static function get_Item_By_Id(int $itemId): Item|false{ 
          // à  remplace array par Item|false une fois que il aura la classe items
        $query = self::execute("SELECT * FROM v_items_status WHERE id = :id", ['id' => $itemId]);
        $data = $query->fetch();
        if ($data === false) { // a remplacer $query->rowCount() == 0 ( lorsque il y aura class Items)
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
       // choper info de l'utilisateur courant
       public static function get_User_By_Id(int $userId): User|false { // remplace array par User|false une fois que il aura la classe users
        $query = self::execute("SELECT * FROM users WHERE id = :id", ['id' => $userId]);
        $data = $query->fetch();// un seul résultat au maximum
        if ($data === false) { // a remplacer $query->rowCount() == 0
            return false;
        } else {
            return new User($data["id"],
             $data["full_name"],
              $data["pseudo"],
               $data["email"],
                $data["role"], 
                $data["picture_path"], 
                $data["iban"]);
        }
    }

//images
    public static function get_Item_Pictures(int $itemId): array {
        $query = self::execute(
            "SELECT * FROM item_pictures WHERE item = :item_id ORDER BY priority ASC", 
            ['item_id' => $itemId]);
    return $query->fetchAll();//retourne un array de pics
    }
   // ...........................................//sam_prophete
    public static function get_Item_Participating(int $userId, string $now): array {
        $query = "SELECT * FROM v_items_status 
                  WHERE id IN (SELECT item FROM bids WHERE owner = :user_id)
                  AND buy_now_reached = 0 
                  AND end_at > :now 
                  ORDER BY end_at DESC";
                  
        $query_result = self::execute($query, ['user_id' => $userId, 'now' => $now]);
        $data = $query_result->fetchAll();
        
        $items = [];
        foreach ($data as $row) {
            $items[] = new Item(
                $row["id"], 
                $row["title"], 
                $row["description"], 
                $row["owner"], 
                $row["created_at"], 
                $row["buy_now_price"] ? (float)$row["buy_now_price"] : null, 
                $row["duration_days"], 
                $row["starting_bid"], 
                $row["end_at"] ?? null,
                $row["bid_count"] ?? 0,
                $row["max_bid"] ? (float)$row["max_bid"] : null,
                (bool)($row["is_direct_sale"] ?? false),
                (bool)($row["is_auction"] ?? false),
                (bool)($row["has_buy_now"] ?? false),
                (bool)($row["has_bids"] ?? false),
                (bool)($row["buy_now_reached"] ?? false),
                (bool)($row["not_purchased_direct_sale"] ?? false)
            );
        }
        
        return $items;
    }
    
    public static function get_Item_Available(int $userId, string $now): array {
        $query = "SELECT * FROM v_items_status 
                  WHERE id NOT IN (SELECT item FROM bids WHERE owner = :user_id)
                  AND owner != :user_id
                  AND buy_now_reached = 0 
                  AND end_at > :now
                  ORDER BY end_at DESC";
                  
        $query_result = self::execute($query, ['user_id' => $userId, 'now' => $now]);
        $data = $query_result->fetchAll();
        
        $items = [];
        foreach ($data as $row) {
            $items[] = new Item(
                $row["id"], 
                $row["title"], 
                $row["description"], 
                $row["owner"], 
                $row["created_at"], 
                $row["buy_now_price"] ? (float)$row["buy_now_price"] : null, 
                $row["duration_days"], 
                $row["starting_bid"], 
                $row["end_at"] ?? null,
                $row["bid_count"] ?? 0,
                $row["max_bid"] ? (float)$row["max_bid"] : null,
                (bool)($row["is_direct_sale"] ?? false),
                (bool)($row["is_auction"] ?? false),
                (bool)($row["has_buy_now"] ?? false),
                (bool)($row["has_bids"] ?? false),
                (bool)($row["buy_now_reached"] ?? false),
                (bool)($row["not_purchased_direct_sale"] ?? false)
            );
        }
        
        return $items;
    }
    
    // Vérifie si l'utilisateur est le meilleur enchérisseur sur un item
    public static function is_Highest_Bidder(int $userId, int $itemId): bool {
        $query = "SELECT owner FROM bids 
                  WHERE item = :item_id 
                  AND amount = (SELECT MAX(amount) FROM bids WHERE item = :item_id)
                  ORDER BY created_at DESC
                  LIMIT 1";
        
        $query_result = self::execute($query, ['item_id' => $itemId]);
        $data = $query_result->fetch();
        
        return $data && (int)$data["owner"] === $userId;
    }
    
    // Vérifie si l'utilisateur a fait une enchère sur un item
    public static function has_Bid_On_Item(int $userId, int $itemId): bool {
        $query = "SELECT COUNT(*) as count FROM bids 
                  WHERE owner = :user_id AND item = :item_id";
        
        $query_result = self::execute($query, ['user_id' => $userId, 'item_id' => $itemId]);
        $data = $query_result->fetch();
        
        return $data && (int)$data["count"] > 0;
    }
    
    // Récupère le pseudo d'un utilisateur par son ID
    public static function get_User_Pseudo_By_Id(int $userId): string {
        $query = self::execute("SELECT pseudo FROM users WHERE id = :id", ['id' => $userId]);
        $data = $query->fetch();
        return $data ? $data['pseudo'] : '';
    }
}