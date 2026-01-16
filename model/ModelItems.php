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


    //Bids 
public static function get_Item_Bids(int $itemId): array {
    $query = self::execute(
        "SELECT b.*, u.pseudo, u.picture_path
         FROM bids b
         JOIN users u ON b.owner = u.id
         WHERE b.item = :item_id
         ORDER BY b.amount DESC, b.created_at DESC",
        ['item_id' => $itemId]
    );
    return $query->fetchAll();
}

public static function has_User_Bid_On_Item(int $userId, int $itemId): bool {
    $query = self::execute(
        "SELECT COUNT(*) 
        FROM bids
         WHERE owner = :user_id AND item = :item_id",
        ['user_id' => $userId, 'item_id' => $itemId]
    );
    $count = $query->fetchColumn();
    return $count > 0;
}

public static function is_User_Highest_Bidder(int $userId, int $itemId): bool {
    $query = self::execute(
        "SELECT owner
         FROM bids
         WHERE item = :item_id
            ORDER BY amount DESC, created_at DESC
         LIMIT 1",
        ['item_id' => $itemId]
    );
    $highestBidderId = $query->fetchColumn();
    return $highestBidderId !== false && (int)$highestBidderId === $userId;
}

public static function get_Highest_Bidder_Pseudo(int $itemId): string|false {
    $query = self::execute(
        "SELECT u.pseudo
         FROM bids b
         JOIN users u ON b.owner = u.id
         WHERE b.item = :item_id
         ORDER BY b.amount DESC, b.created_at DESC
         LIMIT 1",
        ['item_id' => $itemId]
    );
    $pseudo = $query->fetchColumn();
    return $pseudo !== false ? (string)$pseudo : false;
}



public static function create_Bid(int $userId, int $itemId, float $amount, bool $isBuyNow = false): bool {
    //l'item  est ouvert
    $item = self::get_Item_By_Id($itemId);
    if ($item === false) {
        return false;
    }

    //  pas le propriétaire
    if ($item->get_Owner() == $userId) {
        return false;
    }

    //  montant est suffisant
    if (!$isBuyNow && $item->get_Is_Auction()) {
        $minBid = $item->get_Max_Bid() 
            ? $item->get_Max_Bid() + 0.01 
            : $item->get_Starting_Bid();
        
        if ($amount < $minBid) {
            return false;
        }
    }

    // Pour Buy Now : vérifier que c'est le bon montant
    if ($isBuyNow && $item->get_Buy_Now_Price() && $amount != $item->get_Buy_Now_Price()) {
        return false;
    }

    // Insérer le bid dans la base de données
    $now = AppTime::get_current_datetime();
    $query = self::execute(
        "INSERT INTO bids (item, owner, created_at, amount) 
         VALUES (:item_id, :user_id, :created_at, :amount)",
        [
            'item_id' => $itemId,
            'user_id' => $userId,
            'created_at' => $now,
            'amount' => $amount
        ]
    );

    return $query !== false;
}

public static function delete_Item_Pictures(int $itemId): void {
    // sup images
    $pictures = self::get_Item_Pictures($itemId);
    foreach ($pictures as $pic) {
        $path = $pic['picture_path'];
        $thumbPath = str_replace('.jpg', '_thumbnail.jpg', $path);
        if (file_exists($path)) unlink($path);
        if (file_exists($thumbPath)) unlink($thumbPath);
    }
    
    // Supprimer entrées DB
    self::execute(
        "DELETE FROM item_pictures WHERE item = :item_id",
        ['item_id' => $itemId]
    );
}

public static function delete_Item(int $itemId): void {
    self::execute(
        "DELETE FROM items WHERE id = :id",
        ['id' => $itemId]
    );
}
}