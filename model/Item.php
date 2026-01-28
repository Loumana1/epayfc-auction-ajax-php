<?php
require_once "framework/Model.php";
require_once "utils/AppTime.php";
require_once "model/Bid.php";
require_once "model/User.php";
class Item extends Model{

    private $id;
    private$title;
    private $description;
    private$owner;
    private $created_at;
    private $buy_now_price;
    private $duration_days;
    private $starting_bid;

    private ?array $_cached_bids = null;    public $end_at;
    private $bid_count;
    private$max_bid;
    private $is_direct_sale;
    private $is_auction;
    private $has_buy_now;
    private $has_bids;
    private $buy_now_reached;
    private $not_purchased_direct_sale;


    public function __construct(
        int $id,
        string $title,
        ?string $description,
        int $owner,
        string $created_at,
        ?float $buy_now_price,
        int $duration_days,
        float $starting_bid,

        //v_items_status
        ?string $end_at = null,
        int $bid_count = 0,
        float $max_bid= null,
        bool $is_direct_sale = false,
        bool $is_auction = false,
        bool $has_buy_now = false,
        bool $has_bids = false,
        bool $buy_now_reached = false,
        bool $not_purchased_direct_sale = false 
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->owner = $owner;
        $this->created_at = $created_at;
        $this->buy_now_price = $buy_now_price;
        $this->duration_days = $duration_days;
        $this->starting_bid = $starting_bid;
        

        $this->end_at = $end_at;
        $this->bid_count = $bid_count;
        $this->max_bid = $max_bid;
        $this->is_direct_sale = $is_direct_sale;
        $this->is_auction = $is_auction;
        $this->has_buy_now = $has_buy_now;
        $this->has_bids = $has_bids;
        $this->buy_now_reached = $buy_now_reached;
        $this->not_purchased_direct_sale = $not_purchased_direct_sale; 
    }

    /* Centralise la logique de mapping pour éviter la duplication
    */
    private static function rowToItem(array $row): Item {
        return new Item(
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
        

    private static function queryToItems(string $query, array $params): array {
        $result = self::execute($query, $params)->fetchAll();
        return array_map([self::class, 'rowToItem'], $result);
    }
    public function get_Id(): int {
        return $this->id;

    }
    public static function get_by_id(int $id): Item|false {
        $query = self::execute("SELECT * FROM v_items_status WHERE id = :id", ['id' => $id]);
        $data = $query->fetch();
        return $data === false ? false : self::rowToItem($data);
    }



    public function get_Title(): string {
    return $this-> title ;
    }

    public function get_Description(): ?string {
    return $this -> description;

    }
    public function get_owner(): int {
        return $this->owner;
    }
public function get_Created_At() : string {  
    return $this->created_at;
}
public function get_Buy_Now_Price() :?float{
    return $this->buy_now_price;
}


public function get_Starting_Bid(): float {
 return $this -> starting_bid; 
}

    public function get_End_At(): ?string { 
        return $this->end_at;
    }

    public function get_Is_Direct_Sale(): bool {
        return $this->is_direct_sale;
    }

    public function get_Is_Auction(): bool {
        return $this->is_auction;
    }
    public function get_Has_buy_now_price(): bool{

            return $this->buy_now_price !== null;
        

    }




public function is_open(): bool {
        $now = AppTime::get_current_datetime();
        $nowDateTime = new DateTime($now);
        
       //l'item a commencé 
       $createdAt = new DateTime($this->created_at);
       if ($createdAt > $nowDateTime) {
           return false; }
            
        //Direct Sale
            if ($this->is_direct_sale && !$this->is_auction) {
                        return !$this->has_bids_time(); // Pas encore acheté
            }
            
            //  Auction
            if ($this->end_at) {
                $endAtDateTime = new DateTime($this->end_at);
                $isBeforeEnd = $endAtDateTime > $nowDateTime;
                
                // pour tenir compte de AppTime
                $buyNowReachedTime = $this->has_buy_now_reached_time();
                
                return $isBeforeEnd && !$buyNowReachedTime;
            }
        return false;
    }



   

       
    
    public function get_bids(): array {
        if ($this->_cached_bids === null) {
            $this->_cached_bids = Bid::get_by_item($this->id);
        }
        return $this->_cached_bids;
    }
    
    public function get_pictures(): array {
        $query = self::execute(
            "SELECT * FROM item_pictures WHERE item = :id ORDER BY priority ASC",
            ['id' => $this->id]
        );
        return $query->fetchAll();
    }
    
    public function get_seller(): User {
        return User::get_User_By_Id($this->owner);
    }
    
    public function has_bids_time(): bool {
        return count($this->get_bids()) > 0;
    }
    
    public function get_max_bid_time(): ?float {
        $bids = $this->get_bids();
        if (empty($bids)) return null;
        return (float)max(array_column($bids, 'amount'));
    }
    
    public function get_highest_bidder_pseudo(): ?string {
        $bids = $this->get_bids();
        return !empty($bids) ? $bids[0]['pseudo'] : null;
    }
    
    public function get_min_bid_amount(): float {
        $maxBid = $this->get_max_bid_time();
        return $maxBid ? $maxBid + 0.01 : $this->starting_bid;
    }




    public function has_buy_now_reached_time(): bool {
     
        if (!$this->buy_now_price) {
            return false;
        }
        $maxBid = $this->get_max_bid_time();
        return $maxBid !== null && $maxBid >= $this->buy_now_price;
    }






    public static function delete_pictures(int $itemId): void {

    //supp dans bd 
    $query = self::execute(
        "SELECT picture_path FROM item_pictures WHERE item = :id",
        ['id' => $itemId]
    );
    $pictures = $query->fetchAll();
    
    foreach ($pictures as $pic) {
        $path = $pic['picture_path'];
        $thumbPath = str_replace('.jpg', '_thumbnail.jpg', $path);
        if (file_exists($path)) unlink($path);
        if (file_exists($thumbPath)) unlink($thumbPath);
    }
    //supp dans les ficheirb
    
    self::execute(
        "DELETE FROM item_pictures WHERE item = :id",
        ['id' => $itemId]
    );
}
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

    public static function get_main_picture(int $itemId): ?ItemPicture {
        $query = self::execute("SELECT * FROM item_pictures WHERE item = :id AND priority = 1", ["id" => $itemId]);
        $data = $query->fetch();
        if ($data) {
            return new ItemPicture($data['item'], $data['picture_path'], $data['priority']);
        }
        return null;
    }


    public static function get_User_Pseudo_By_Id(int $userId): string {
        $query = self::execute("SELECT pseudo FROM users WHERE id = :id", ['id' => $userId]);
        $data = $query->fetch();
        return $data ? $data['pseudo'] : '';
    }
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
}